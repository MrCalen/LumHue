<?php

declare(strict_types=1);

namespace App\WebSockets;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Connection
{
    protected $connection;
    protected $name;

    public function __construct(ConnectionInterface $connection) {
        $this->connection = $connection;
    }

    public function getConnection() : ConnectionInterface {
        return $this->connection;
    }

    public function setName(string $name) {
        $this->name = $name;
    }

    public function getName() : string {
        return $this->name;
    }

    public function send($message) {
        $this->connection->send($message);
    }
}
