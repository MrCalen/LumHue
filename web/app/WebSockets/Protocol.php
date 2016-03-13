<?php
namespace App\WebSockets;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\WebSockets\Connection;
use App\WebSockets\Strategy\StrategyInterface;
use Exception;

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
    echo "New connection! ({$connection->resourceId})\n";
    $this->openConnections[$connection->resourceId] = new Connection($connection);
  }

  public function onMessage(ConnectionInterface $connection, $message)
  {
    echo 'OnMessage';
    var_dump($message);
    $this->strategy->onMessage($connection, $message, $this);
  }

  public function onClose(ConnectionInterface $connection)
  {
    echo "Connection {$connection->resourceId} has disconnected\n";
    unset($this->openConnections[$connection->resourceId]);
    $this->strategy->onClose($this);
  }

  public function onError(ConnectionInterface $connection, Exception $e)
  {
    echo "An error has occurred: {$e->getMessage()}\n";
    $connection->close();
    unset($this->openConnections[$connection->resourceId]);
  }

  public function getConnections()
  {
    return $this->openConnections;
  }
}
