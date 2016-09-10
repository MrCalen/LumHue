<?php

declare(strict_types = 1);

namespace App\WebSockets\Strategy;

use App\Helpers\WebServices\LuisApiHelper;
use App\Helpers\WebServices\SpeechApiHelper;
use App\WebSockets\Protocol;
use JWAuth;
use Ratchet\ConnectionInterface;

class AudioStrategy implements StrategyInterface
{
    private $protocol;

    public function setProtocol(Protocol $p)
    {
        $this->protocol = $p;
    }

    public function getName() : string
    {
        return "ws:audio";
    }

    public function onMessage(ConnectionInterface $connection, string $message, Protocol $protocol)
    {
        // Skip ping messages
        $json = json_decode($message);
        if ($json) {
            if ($json->type === 'auth') {
                $token = $json->data->token;
                \JWTAuth::setToken($token);
                $user = \JWTAuth::toUser();
                if (!$user) {
                    return;
                }
                $client = $protocol->getConnections()[$connection->resourceId];
                $client->setName($json->data->name);
                $client->setToken($token);
            }
            return;
        }
        $client = $protocol->getConnections()[$connection->resourceId];
        if (!$client || !$client->getToken()) {
            return;
        }

        $token = $client->getToken();
        \JWTAuth::setToken($token);
        $user = \JWTAuth::toUser();
        if (!$user) {
            return;
        }
        $uniq = time();
        $filename = '/tmp/' . $uniq . '.wav'; // 16000
        $filename_sampled = '/tmp/' . $uniq . '.sampled.wav';
        file_put_contents($filename, $message);
        shell_exec('sox ' . $filename . ' ' . $filename_sampled . ' rate 16k');
        $message = file_get_contents($filename_sampled);
        $result = SpeechApiHelper::sendBinary($message);
        unlink($filename);
        unlink($filename_sampled);
        if (!isset($result->results)) {
            $client->send(json_encode([
                'result' => 'KO',
                'reason' => 'no result found',
            ]));
            return;
        }

        $result = $result->results[0]->name;
        $client->send(json_encode([
            'content' => $result,
            'type' => 'message',
            'author' => $client->getName(),
            'date' => date('l jS \of F Y h:i:s A'),
        ]));

        try {
            $luisresponse = LuisApiHelper::getIntent($result, $user->meethue_token);
        } catch (Throwable $e) {
            $client->send(json_encode([
                'result' => 'KO',
                'reason' => 'Error with Luis',
            ]));
            return;
        }

        // FIXME: Handle when results are not confident enough
        $client->send(json_encode([
            'type' => 'message',
            'content' => $luisresponse['message'],
            'date' => date('l jS \of F Y h:i:s A'),
        ]));
    }

    public function onClose(ConnectionInterface $connection, Protocol $protocol)
    {
        // TODO: Implement onClose() method.
    }
}
