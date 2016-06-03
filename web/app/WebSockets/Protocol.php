<?php

declare(strict_types=1);

namespace App\WebSockets;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\WebSockets\Strategy\StrategyInterface;
use Exception;
use MongoHue;

class Protocol implements MessageComponentInterface
{
    protected $openConnections;
    protected $strategy;

    public function __construct(StrategyInterface $strategy)
    {
        $this->openConnections = [];
        $this->strategy = $strategy;
    }

    public function onOpen(ConnectionInterface $connection)
    {
        $this->openConnections[$connection->resourceId] = new Connection($connection);
    }

    public function onMessage(ConnectionInterface $connection, $message)
    {
        $this->strategy->onMessage($connection, $message, $this);
    }

    public function onClose(ConnectionInterface $connection)
    {
        $this->strategy->onClose($connection, $this);
        unset($this->openConnections[$connection->resourceId]);
    }

    public function onError(ConnectionInterface $connection, Exception $e)
    {
        var_dump($e);
        $connection->close();
        unset($this->openConnections[$connection->resourceId]);
    }

    public function getConnections() : array
    {
        return $this->openConnections;
    }

    public function keepLog($message, $connection)
    {
        date_default_timezone_set('Europe/Paris');
        MongoHue::table('huechat_log')
                ->insertOne([
                    'message' => $message,
                    'user' => $this->openConnections[$connection->resourceId]->getName(),
                    'date' => date('l jS \of F Y h:i:s A'),
                ]);
    }
}
