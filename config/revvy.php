<?php

return [
    /**
     * Настройки пользователя Revvy
     */
    'name' => 'ИМЯ_ПОЛЬЗОВАТЕЛЯ',
    'password' => 'ПАРОЛЬ',
    'token_expiration_days' => 30,
    'baseurl' => 'https://revvy.ru',

    /**
     * Настройки базы данных
     */
    'db_host' => 'localhost',
    // 'db_port' => 3306
    'db_name' => 'ИМЯ_БАЗЫ_ДАННЫХ',
    'db_user' => 'ИМЯ_ПОЛЬЗОВАТЕЛЯ_БАЗЫ_ДАННЫХ',
    'db_password' => 'ПАРОЛЬ_БАЗЫ_ДАННЫХ',
    'db_table' => 'revvy_token', // таблица в базе данных для хранения токена авторизации
];