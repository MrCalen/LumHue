<?php
namespace App\WebSockets;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\WebSockets\Connection;
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
    $this->keepLog("New connection! ({$connection->resourceId})", $connection);
  }

  public function onMessage(ConnectionInterface $connection, $message)
  {
    $this->strategy->onMessage($connection, $message, $this);
  }

  public function onClose(ConnectionInterface $connection)
  {
    $this->keepLog("Connection {$this->openConnections[$connection->resourceId]->getName()} has disconnected", $connection);
    unset($this->openConnections[$connection->resourceId]);
    $this->strategy->onClose($this);
  }

  public function onError(ConnectionInterface $connection, Exception $e)
  {
    $this->keepLog("An error has occurred: {$e->getMessage()}", $connection);
    echo ("An error has occurred: {$e->getMessage()}\n");
    $connection->close();
    unset($this->openConnections[$connection->resourceId]);
  }

  public function getConnections()
  {
    return $this->openConnections;
  }

  public function keepLog($message, $connection)
  {
    MongoHue::table('huechat_log')
              ->insertOne([
                'message' => $message,
                'user' => $this->openConnections[$connection->resourceId]->getName(),
                'date' => date('l jS \of F Y h:i:s A'),
              ]);
  }
}
