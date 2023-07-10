<?php

namespace MaximYugov\RevvyApi;

class RevvyApi
{
    /**
     * Имя пользователя
     * 
     * @var string
     */
    private string $name;

    /**
     * Пароль
     * 
     * @var string
     */
    private string $password;

    public function __construct()
    {
        $config = require './config/revvy.php';
        $this->name = $config['name'];
        $this->password = $config['password'];

        return $this;
    }
    /**
     * Получение токена авторизации
     */
    public function getAuthToken()
    {
        $params = [
            'name' => $this->name,
            'password' => $this->password,
        ];

        $response = $this->sendPostRequest('/api/authentication', $params);

        //TODO реализовать запись токена в БД
    }

    /**
     * Проверка валидности токена авторизации
     */
    public function isValidAuthToken()
    {
        // Проверка токена на срок действия
    }
    
    /**
     * Отправка GET-запроса
     * 
     * @param string $url
     * @param array $params
     * 
     * @return array
     */
    public function sendGetRequest(string $url, array $params): array
    {
        $url = $url . '?' . http_build_query($params);

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
    public function sendPostRequest(string $url, array $params): array
    {
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