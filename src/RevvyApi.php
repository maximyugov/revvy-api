<?php

declare(strict_types=1);

namespace MaximYugov\RevvyApi;

class RevvyApi
{
    /**
     * Массив с настройками
     * 
     * @var array
     */
    private array $config;

    /**
     * Текущий актуальный токен авторизации
     * 
     * @var string
     */
    private string $token;

    /**
     * Подключение к базе данных
     * 
     * @var \PDO
     */
    private \PDO $dbConnection;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/revvy.php';

        $this->dbConnection = new \PDO("mysql:host={$this->config['localhost']};dbname={$this->config['db_name']}",
                        $this->config['db_user'],
                        $this->config['db_password']);

        $this->token = $this->validatedToken();
    }

    /**
     * Отправка запроса к Revvy API
     * 
     * @param string $url
     * @param array $params
     * @param string $method
     * 
     * @return array
     */
    public function sendRequest(string $url, array $params, string $method = 'GET', bool $authRequired = true): array
    {
        $url = $this->config['baseurl'] . $url;

        $ch = curl_init();
        $headers = ['Content-Type: application/json'];

        if ($authRequired) {
            $headers[] = "Authorization: Bearer {$this->token}";
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($method === 'GET') {
            $url .= '?' . http_build_query($params);
        }
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Возвращает текущий валидный токен или генерирует новый
     * 
     * @return string
     */
    private function validatedToken(): string
    {
        $tokenData = $this->getCurrentToken();

        if ($this->isValidToken($tokenData)) {
            return $tokenData['token'];
        }

        $tokenData = $this->generateToken();
        $this->saveToken($tokenData);        

        return $tokenData['token'];
    }

    /**
     * Проверка валидности токена авторизации
     * 
     * @param array $tokenData
     * 
     * @return bool
     */
    private function isValidToken(array $tokenData): bool
    {
        $tokenCreatedAt = new \DateTime($tokenData['created_at']);
        $now = new \DateTime();
        $interval = $tokenCreatedAt->diff($now);

        if ($interval->days <= $this->config['token_expiration_days']) {
            return true;
        }

        return false;
    }

    /**
     * Получение токена авторизации
     */
    private function generateToken(): array
    {
        $params = [
            'name' => $this->config['name'],
            'password' => $this->config['password'],
        ];

        $response = $this->sendRequest('/api/authentication', $params, 'POST', false);

        return $response;
    }

    /**
     * Запись информации о токене в БД
     * 
     * @param array $tokenData
     */
    private function saveToken(array $tokenData): void
    {
        $params = [
            ':token' => $tokenData['token'],
            ':userName' => $tokenData['userName'],
            ':userId' => $tokenData['userId'],
            ':createdAt' => $tokenData['createdAt'],
        ];

        try {
            $this->dbConnection->beginTransaction();
            
            // Удаляем старый токен
            $query = "DELETE FROM `{$this->config['db_table']}`";
            $stmt = $this->dbConnection->prepare($query);
            $stmt->execute();
            
            // Записываем новый токен
            $query = "INSERT INTO `{$this->config['db_table']}` (`token`, `user_name`, `user_id`, `created_at`)
                        VALUES (:token, :userName, :userId, :createdAt)";
            $stmt = $this->dbConnection->prepare($query);
            $stmt->execute($params);

            $this->dbConnection->commit();
          } catch (\Exception $e) {
            $this->dbConnection->rollBack();
            echo "Ошибка: " . $e->getMessage();
          }
    }

    /**
     * Получение данных о текущем токене
     */
    private function getCurrentToken(): array
    {
        $tokenData = $this->dbConnection->query("SELECT * FROM {$this->config['db_table']}")->fetchAll(\PDO::FETCH_ASSOC);

        return current($tokenData);
    }
}