<?php

declare(strict_types = 1);

namespace App\WebSockets\Strategy;

use App\Helpers\WebServices\LuisApiHelper;
use App\WebSockets\Bot;
use App\WebSockets\Protocol;
use Ratchet\ConnectionInterface;

class ChatStrategy implements StrategyInterface
{
    public function onMessage(ConnectionInterface $connection, string $realmessage, Protocol $protocol)
    {
        date_default_timezone_set('Europe/Paris');
        $message = json_decode($realmessage);
        $token = $message->token;
        \JWTAuth::setToken($token);
        $user = \JWTAuth::toUser();
        if (!$user) {
            return;
        }
        $client = $protocol->getConnections()[$connection->resourceId];
        $bot = Bot::instance();

        if ($message->type === 'auth') {
            $name = $message->data->name;
            $protocol->getConnections()[$connection->resourceId]->setName($name);
            if (!isset($message->mobile)) {
                $client->send(json_encode($bot->onConnect($name, $user->id)));
            }
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

            $luisresponse = LuisApiHelper::getIntent($content, $user->meethue_token);
            $client->send(json_encode([
                'content' => $luisresponse['message'],
                'type' => 'message',
                'date' => date('l jS \of F Y h:i:s A'),
            ]));
        } elseif ($message->type === 'beacon') {
            // MongoHue::table('beacon_data')
            //    ->insert($message);
        }
    }

    public function onClose(ConnectionInterface $connection, Protocol $protocol)
    {
        date_default_timezone_set('Europe/Paris');
        $names = array_map(function ($elt) use ($connection) {
            if ($connection->resourceId !== $elt->getConnection()->resourceId) {
                return $elt->getName();
            }
            return null;
        }, array_values($protocol->getConnections()));
        $names = array_filter($names, function ($elt) {
            return $elt != null;
        });
        $message = json_encode([
            'type' => 'auth',
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
