<?php

namespace App\Console\Commands;

use App\Events\MqttMessageReceived;
use Illuminate\Console\Command;
use App\Services\MqttService;
use PhpMqtt\Client\Exceptions\ConfigurationInvalidException;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\InvalidMessageException;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Exceptions\ProtocolViolationException;
use PhpMqtt\Client\Exceptions\RepositoryException;

class MqttReceiveData extends Command
{
    protected $signature = 'mqtt:receive-data';
    protected $description = 'Subscribe to an MQTT topic and broadcast received messages';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws ConnectingToBrokerFailedException
     * @throws MqttClientException
     * @throws RepositoryException
     * @throws ConfigurationInvalidException
     * @throws ProtocolViolationException
     * @throws InvalidMessageException
     * @throws DataTransferException
     */
    public function handle(): void
    {
        $mqttService = new MqttService();
        $mqttService->connect();
        $mqttService->subscribe('your/mqtt/topic', function ($topic, $message) {
            broadcast(new MqttMessageReceived($topic, $message));
            $this->info("Received message on topic {$topic}: {$message}");
        });
        $mqttService->loop();
    }
}
