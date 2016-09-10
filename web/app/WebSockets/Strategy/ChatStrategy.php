<?php

declare(strict_types = 1);

namespace App\WebSockets\Strategy;

use App\Helpers\WebServices\LuisApiHelper;
use App\WebSockets\Bot;
use App\WebSockets\Protocol;
use Redis;
use Ratchet\ConnectionInterface;

class ChatStrategy implements StrategyInterface
{
    private $protocol;

    public function __construct()
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1');

        /*
         * Message like: {'light_id': '1', 'color': '#213', 'token' : '****'}
         */
        $redis->subscribe(['ws'], function ($redis, $chan, $msg) {
            $message = json_decode($msg);
            if (!$message) {
                return;
            }
            $token = $message->token;
            \JWTAuth::setToken($token);
            $user = \JWTAuth::toUser();
            if (!$user) {
                return;
            }

            foreach ($this->protocol->getConnections() as $connection) {
                if ($connection->getId() != $user->id) {
                    continue;
                }
                $connection->getConnection()->send(json_encode([
                    'content' => [
                        'light_id' => $message->light_id,
                        'color' => $message->color,
                    ],
                    'type' => 'apply',
                    'date' => date('l jS \of F Y h:i:s A'),
                ]));
            }
        });
    }

    public function setProtocol(Protocol $protocol)
    {
        $this->protocol = $protocol;
    }

    public function getName() : string
    {
        return "ws:chat";
    }

    public function onMessage(ConnectionInterface $connection, string $realmessage, Protocol $protocol)
    {
        date_default_timezone_set('Europe/Paris');
        $message = json_decode($realmessage);
        if (!$message) {
            return;
        }
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
            $protocol->getConnections()[$connection->resourceId]->setId($user->id);
            $client->send(json_encode($bot->onConnect($name, $user->id, isset($message->mobile))));
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

            foreach ($protocol->getConnections() as $connection) {
                if ($connection->getId() != $user->id) {
                    continue;
                }
                $connection->getConnection()->send(json_encode([
                    'content' => $luisresponse['message'],
                    'type' => 'message',
                    'date' => date('l jS \of F Y h:i:s A'),
                ]));
            }

        } elseif ($message->type === 'beacon') {
            // MongoHue::table('beacon_data')
            //    ->insert($message);
        }
    }

    public function onClose(ConnectionInterface $connection, Protocol $protocol)
    {
    }
}
