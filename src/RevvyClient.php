<?php

namespace MaximYugov\RevvyApi;

use MaximYugov\RevvyApi\RevvyApi;

class RevvyClient
{
    protected RevvyApi $client;

    public function __construct()
    {
        $this->client = new RevvyApi();
    }

    public function getReviews(\DateTime $from, \DateTime $to, string $placeId, int $messageType): array
    {
        $params = [
            'from' => $from->format("Y-m-d\\TH:i:s"),
            'to' => $to->format("Y-m-d\\TH:i:s"),
            'place_id' => $placeId,
            'message_type' => $messageType,
        ];

        $response = $this->client->sendRequest('/api/chats/list', $params, 'GET');

        return $response;
    }
}