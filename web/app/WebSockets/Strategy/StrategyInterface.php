<?php

declare(strict_types=1);

namespace App\WebSockets\Strategy;

use App\WebSockets\Protocol;
use Ratchet\ConnectionInterface;

interface StrategyInterface
{
    public function onMessage(ConnectionInterface $connection, string $message, Protocol $protocol);
    public function onClose(ConnectionInterface $connection, Protocol $protocol);
}
