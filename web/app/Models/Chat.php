<?php namespace App\Models;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
  protected $clients;
  protected $client_names;
  protected $i = 0;

  public function __construct()
  {
    $this->clients = new \SplObjectStorage;
    $this->client_names = [];
  }

  public function onOpen(ConnectionInterface $conn)
  {
    // Store the new connection to send messages to later
    $this->clients->attach($conn);
    echo "New connection! ({$conn->resourceId})\n";
  }

  public function onMessage(ConnectionInterface $from, $msg)
  {
    $json_msg = json_decode($msg);
    $message = '';
    if ($json_msg->type === 'auth')
    {
      $this->client_names[$from->resourceId] = $json_msg->data->name;
      $message = json_encode([
        'type'  => 'auth',
        'users' =>  array_values($this->client_names),
        ]
      );
    }
    else if ($json_msg->type === 'message')
    {
      var_dump($json_msg);
      $message = json_encode([
        'type' => 'message',
        'content' => $json_msg->content,
        'author' => $this->client_names[$from->resourceId],
        'date' => date('l jS \of F Y h:i:s A'),
      ]);
    }

    foreach ($this->clients as $client)
        $client->send($message);
  }

  public function onClose(ConnectionInterface $conn)
  {
    // The connection is closed, remove it, as we can no longer send it messages
    $this->clients->detach($conn);
    unset($this->client_names[$conn->resourceId]);
    echo "Connection {$conn->resourceId} has disconnected\n";
    $message = json_encode([
      'type'  => 'auth',
      'users' =>  array_values($this->client_names),
      ]
    );
    foreach ($this->clients as $client)
        $client->send($message);
  }

  public function onError(ConnectionInterface $conn, \Exception $e)
  {
    echo "An error has occurred: {$e->getMessage()}\n";
    $conn->close();
  }
}
