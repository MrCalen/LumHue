<?php

namespace App\WebSockets;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Connection
{
  protected $connection;
  protected $name;

  public function __construct($connection)
  {
    $this->connection = $connection;
  }

  public function getConnection()
  {
    return $this->connection;
  }

  public function setName(string $name)
  {
    $this->name = $name;
  }

  public function getName()
  {
    return $this->name;
  }

  public function send($message)
  {
    $this->connection->send($message);
  }
}

?>
