<?php

namespace App;

use MaximYugov\RevvyApi\MessageType;
use MaximYugov\RevvyApi\RevvyClient;

$revvy = new RevvyClient();
$placeId = '2df530d4-e1f9-49ee-018f-08d920f3e804'; // Идентификатор филиала

// Получаем положительные отзывы с 1 июля 2023
$positive_reviews = $revvy->getReviews(new \DateTime('2023-07-01 00:00:00'), new \DateTime(), $placeId, MessageType::POSITIVE_FEEDBACK);

foreach ($positive_reviews['messages'] as $review) {
    echo "<p>{$review['message']}</p>";
}