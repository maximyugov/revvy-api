<?php

namespace MaximYugov\RevvyApi;

use DateTime;

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

    public function __construct()
    {
        $this->config = require '../config/revvy.php';

        $this->token = $this->validatedToken();

        // Проверить срок действия токена.
        // Если срок вышел, сделать запрос на новый токен
        // Записать новый токен в базу
        // Если срок не вышел, взять текущий токен

        // Сохранить в переменную токен
    }

    /**
     * Возвращает текущий валидный токен или генерирует новый
     * 
     * @return string
     */
    public function validatedToken(): string
    {
        $tokenData = $this->getCurrentToken();

        if ($this->isValidToken($tokenData)) {
            return $tokenData['token'];
        }

        $tokenData = $this->getAuthToken();
        $this->saveAuthToken($tokenData);        

        return $tokenData['token'];
    }

    /**
     * Проверка валидности токена авторизации
     * 
     * @param array $tokenData
     * 
     * @return bool
     */
    public function isValidToken(array $tokenData): bool
    {
        $tokenCreatedAt = new DateTime($tokenData['created_at']);
        $now = new DateTime();
        $interval = $tokenCreatedAt->diff($now);

        if ($interval->days <= $this->config['token_expiration_days']) {
            return true;
        }

        return false;
    }

    public function sendRequest(string $url, array $params, string $method = 'GET'): array
    {
        if ($method === 'GET') {
            return $this->sendGetRequest($url, $params);
        }
        
        if ($method === 'POST') {
            return $this->sendPostRequest($url, $params);
        }
    }

    /**
     * Получение токена авторизации
     */
    private function getAuthToken(): array
    {
        $params = [
            'name' => $this->config['name'],
            'password' => $this->config['password'],
        ];

        $response = $this->sendPostRequest('/api/authentication', $params);

        return $response;
    }

    /**
     * Запись информации о токене в БД
     * 
     * @param array $tokenData
     */
    private function saveAuthToken(array $tokenData)
    {
        $params = [
            ':jwtToken' => $tokenData['jwtToken'],
            ':userName' => $tokenData['userName'],
            ':userId' => $tokenData['userId'],
            ':createdAt' => $tokenData['createdAt'],
        ];

        //TODO реализовать запись токена в БД
        $db = new \PDO("mysql:host={$this->config['localhost']};dbname={$this->config['db_name']}",
                        $this->config['db_user'],
                        $this->config['db_password']);
        $query = "INSERT INTO `{$this->config['db_table']}` (`jwtToken`, `user_name`, `user_id`, `created_at`)
                    VALUES (:jwtToken, :userName, :userId, :createdAt)";
        $stmt = $db->prepare($query);
        $stmt->execute($params);
    }

    /**
     * Получение данных о текущем токене
     */
    private function getCurrentToken(): array
    {
        $tokenData = $this->dbConnection->query("SELECT * FROM {$this->config['db_table']}")->fetchAll(\PDO::FETCH_ASSOC);

        return $tokenData[0];
    }
    
    /**
     * Отправка GET-запроса
     * 
     * @param string $url
     * @param array $params
     * 
     * @return array
     */
    private function sendGetRequest(string $url, array $params): array
    {
        $url = $this->config['baseurl'] . $url . '?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }

    /**
     * Отправка POST-запроса
     * 
     * @param string $url
     * @param array $params
     * 
     * @return array
     */
    private function sendPostRequest(string $url, array $params): array
    {
        $url = $this->config['baseurl'] . $url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }
}