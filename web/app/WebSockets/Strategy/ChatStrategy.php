<?php

declare(strict_types=1);

namespace App\WebSockets\Strategy;

use App\WebSockets\Bot;
use App\WebSockets\Protocol;
use Ratchet\ConnectionInterface;

class ChatStrategy implements StrategyInterface
{
    private function broadcast(Protocol $protocol, string $message, ConnectionInterface $connection)
    {
        $username = $protocol->getConnections()[$connection->resourceId]->getName();
        $protocol->keepLog("New message {$message} from {$username}", $connection);
        foreach ($protocol->getConnections() as $name => $client) {
            $client->send($message);
        }
    }

    public function onMessage(ConnectionInterface $connection, string $message, Protocol $protocol)
    {
        date_default_timezone_set('Europe/Paris');
        $message = json_decode($message);
        if ($message->type === 'auth') {
            $name = $message->data->name;
            $protocol->getConnections()[$connection->resourceId]->setName($name);
            $names = array_map(function ($elt) {
                return $elt->getName();
            }, array_values($protocol->getConnections()));
            $msg = json_encode([
                'type'  => 'auth',
                'users' =>  $names,
            ]);
        } elseif ($message->type === 'message') {
            $msg = json_encode([
                'type' => 'message',
                'content' => $message->content,
                'author' => $protocol->getConnections()[$connection->resourceId]->getName(),
                'date' => date('l jS \of F Y h:i:s A'),
            ]);
        } elseif ($message->type === 'bot') {
            $msg = json_encode([
                'type' => 'bot',
                'content' => $message->content,
                'author' => $protocol->getConnections()[$connection->resourceId]->getName(),
                'date' => date('l jS \of F Y h:i:s A'),
            ]);
            $bot = Bot::instance();
            $return = $bot->handleMessage($message->content);
            $client = $protocol->getConnections()[$connection->resourceId];
            $return = json_encode([
                'content' => $return,
                'type' => 'message',
                'author' => $client->getName(),
            ]);
            $client->send($return);
            return;
        }

        if (isset($msg)) {
            $this->broadcast($protocol, $msg, $connection);
        }
    }

    public function onClose(ConnectionInterface $connection, Protocol $protocol)
    {
        date_default_timezone_set('Europe/Paris');
        $names = array_map(function ($elt) use ($connection) {
            if ($connection->resourceId !== $elt->getConnection()->resourceId) {
                return $elt->getName();
            }
        }, array_values($protocol->getConnections()));
        $names = array_filter($names, function ($elt) {
            return $elt != null;
        });
        $message = json_encode([
            'type'  => 'auth',
            'users' => $names,
        ]);

        $name = $protocol->getConnections()[$connection->resourceId]->getName();
        $botMessage = json_encode([
            'type' => 'message',
            'content' => "{$name} disconnected",
            'author' => 'LumHue Bot',
            'date' => date('l jS \of F Y h:i:s A'),
        ]);
        foreach ($protocol->getConnections() as $name => $client) {
            $client->send($message);
            $client->send($botMessage);
        }
    }
}
