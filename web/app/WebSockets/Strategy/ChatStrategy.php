<?php

namespace App\WebSockets\Strategy;

use App\WebSockets\Protocol;

class ChatStrategy implements StrategyInterface
{
  private function broadcast(Protocol $protocol, $message)
  {
    foreach ($protocol->getConnections() as $name => $client)
        $client->send($message);
  }

  public function onMessage($connection, $message, Protocol $protocol)
  {
    $message = json_decode($message);
    if ($message->type === 'auth')
    {
      $name = $message->data->name;
      $protocol->getConnections()[$connection->resourceId]->setName($name);
      $names = array_map(function ($elt) {
        return $elt->getName();
      }, array_values($protocol->getConnections()));
      $msg = json_encode([
        'type'  => 'auth',
        'users' =>  $names,
        ]);
    }
    else if ($message->type === 'message')
    {
      $msg = json_encode([
        'type' => 'message',
        'content' => $message->content,
        'author' => $protocol->getConnections()[$connection->resourceId]->getName(),
        'date' => date('l jS \of F Y h:i:s A'),
      ]);
    }
    if (isset($msg))
      $this->broadcast($protocol, $msg);
  }

  public function onClose(Protocol $protocol)
  {
    $message = json_encode([
      'type'  => 'auth',
      'users' =>  array_values($protocol->getConnections()),
      ]);

    foreach ($protocol->getConnections() as $name => $connection)
        $connection->send($message);
    $this->broadcast($protocol, $message);
  }

}
