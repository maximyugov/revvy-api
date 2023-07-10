<?php

namespace MaximYugov\RevvyApi;

class RevvyApi
{
    /**
     * Массив с настройками
     * 
     * @var array
     */
    private array $config;

    public function __construct()
    {
        $this->config = require './config/revvy.php';
    }
    /**
     * Получение токена авторизации
     */
    public function getAuthToken()
    {
        $params = [
            'name' => $this->config['name'],
            'password' => $this->config['password'],
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

    public function sendRequest(string $url, array $params, string $method): array
    {
        if ($method === 'GET') {
            return $this->sendGetRequest($url, $params);
        }
        
        if ($method === 'POST') {
            return $this->sendPostRequest($url, $params);
        }
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
    private function sendPostRequest(string $url, array $params): array
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