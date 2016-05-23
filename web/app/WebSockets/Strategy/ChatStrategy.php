<?php

declare(strict_types=1);

namespace App\WebSockets\Strategy;

use App\Helpers\WebServices\LuisApiHelper;
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
        $token = $message->token;
        \JWTAuth::setToken($token);
        $user = \JWTAuth::toUser();
        if (!$user)
            return;
        $client = $protocol->getConnections()[$connection->resourceId];
        $bot = Bot::instance();

        if ($message->type === 'auth') {
            $name = $message->data->name;
            $protocol->getConnections()[$connection->resourceId]->setName($name);
            $client->send(json_encode($bot->onConnect($name, $user->id)));
            return;
        } elseif ($message->type === 'message') {
            // Handle message with luis
            $content = $message->content;
            $client->send(json_encode([
                'content' => $content,
                'type' => 'message',
                'author' => $client->getName(),
                'date' => date('l jS \of F Y h:i:s A'),
            ]));

            $luisresponse = LuisApiHelper::GetIntent($content, $user->meethue_token);
            $client->send(json_encode([
                'content' => $luisresponse['message'],
                'type' => 'message',
                'date' => date('l jS \of F Y h:i:s A'),
            ]));
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
