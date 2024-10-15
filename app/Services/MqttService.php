<?php

namespace App\Services;

use PhpMqtt\Client\Exceptions\ConfigurationInvalidException;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\InvalidMessageException;
use PhpMqtt\Client\Exceptions\ProtocolNotSupportedException;
use PhpMqtt\Client\Exceptions\ProtocolViolationException;
use PhpMqtt\Client\Exceptions\RepositoryException;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\Exceptions\MqttClientException;

class MqttService
{
    protected MqttClient $client;

    /**
     * @throws ProtocolNotSupportedException
     */
    public function __construct()
    {
        $host = env('MQTT_HOST');
        $port = env('MQTT_PORT');
        $clientId = uniqid('mqtt_');
        $this->client = new MqttClient($host, $port, $clientId);
    }

    /**
     * @throws ConfigurationInvalidException
     * @throws ConnectingToBrokerFailedException
     */
    public function connect(): void
    {
        $this->client->connect(env('MQTT_USERNAME'), env('MQTT_PASSWORD'));
    }

    /**
     * @throws RepositoryException
     * @throws DataTransferException
     */
    public function subscribe($topic, callable $callback): void
    {
        $this->client->subscribe($topic, $callback, 0);
    }

    /**
     * @throws ProtocolViolationException
     * @throws InvalidMessageException
     * @throws MqttClientException
     * @throws DataTransferException
     */
    public function loop($seconds = 0): void
    {
        $this->client->loop($seconds);
    }
}
