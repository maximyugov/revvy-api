<?php

namespace MaximYugov\RevvyApi;

class MessageType
{
    /**
     * Запрос обратной связи (первое сообщение)
     * 
     * @var int
     */
    const FEEDBACK_REQUEST_FIRST = 0;
    
    /**
     * Запрос отзыва (второе сообщение)
     * 
     * @var int
     */
    const FEEDBACK_REQUEST_SECOND = 1;
    
    /**
     * Нейтральное сообщение клиента
     * 
     * @var int
     */
    const NEUTRAL_MESSAGE = 2;
    
    /**
     * Ответ на автоматическое сообщения в случае отрицательного отзыва
     * 
     * @var int
     */
    const AUTO_RESPONSE = 3;
    
    /**
     * Отрицательный отзыв
     * 
     * @var int
     */
    const NEGATIVE_FEEDBACK = 4; 

    /**
     * Положительный отзыв 
     * 
     * @var int
     */
    const POSITIVE_FEEDBACK = 5;
    
    /**
     * Ответ от организации
     * 
     * @var int
     */
    const RESPONSE_FROM_ORGANIZATION = 6;
    
    /**
     * Уведомление от организации
     * 
     * @var int
     */
    const NOTIFICATION_FROM_ORGANIZATION = 7;
    
    /**
     * Дополнительная информация от клиента
     * 
     * @var int
     */
    const ADDITIONAL_INFO_FROM_CLIENT = 8;
    
    /**
     * Ответ на дополнительную информацию от пользователя
     * 
     * @var int
     */
    const RESPONSE_TO_ADDITIONAL_INFO_FROM_CLIENT = 9;
    
    /**
     * Сообщение из массовой рассылки
     * 
     * @var int
     */
    const MESSAGE_FROM_MASS_MAILING = 10;
    
    /**
     * Сообщение для дополнительной продажи
     * 
     * @var int
     */
    const MESSAGE_FOR_UPSELL = 11;
    
    /**
     * Запрос подтверждения записи в УС
     * 
     * @var int
     */
    const CONFIRMATION_REQUEST = 12;
    
    /**
     * Подтверждение записи в УС
     * 
     * @var int
     */
    const CONFIRMATION = 13;
    
    /**
     * Отмена записи в УС
     * 
     * @var int
     */
    const CANCELLATION = 14;
    
    /**
     * Ответ организации на подтверждение записи в УС
     * 
     * @var int
     */
    const RESPONSE_TO_CONFIRMATION = 15;
    
    /**
     * Ответ организации на отмену записи в УС
     * 
     * @var int
     */
    const RESPONSE_TO_CANCELLATION = 16;
    
    /**
     * Пояснения клиента к негативному отзыву
     * 
     * @var int
     */
    const CLIENT_EXPLANATION_TO_NEGATIVE_FEEDBACK = 17;
    
    /**
     * Уведомление об изменении заказа в Iiko
     * 
     * @var int
     */
    const IIKO_ORDER_MODIFICATION_NOTIFICATION = 18;
}