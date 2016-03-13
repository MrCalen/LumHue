<?php

namespace App\WebSockets\Strategy;

use App\WebSockets\Protocol;

interface StrategyInterface
{
  public function onMessage($connection, $message, Protocol $protocol);
  public function onClose($connection, Protocol $protocol);
}
