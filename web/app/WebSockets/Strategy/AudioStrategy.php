<?php

declare(strict_types=1);

namespace App\WebSockets\Strategy;

use App\Helpers\SpeechAPI\SpeechApiHelper;
use App\WebSockets\Protocol;
use Ratchet\ConnectionInterface;

class AudioStrategy implements StrategyInterface
{

    public function onMessage(ConnectionInterface $connection, string $message, Protocol $protocol)
    {
        // Skip ping messages
        if (json_decode($message))
           return;
        $uniq = time();
        $filename = '/tmp/' . $uniq . '.wav';
        $filename_sampled = '/tmp/' . $uniq . '.sampled.wav';
        file_put_contents($filename, $message);
        shell_exec('sox ' . $filename . ' ' . $filename_sampled . ' rate 16k');
        $message = file_get_contents($filename_sampled);
        $result = SpeechApiHelper::SendBinary($message);
        var_dump($result);
        unlink($filename);
        unlink($filename_sampled);
        $client = $protocol->getConnections()[$connection->resourceId];
        $client->send(json_encode($result));
    }

    public function onClose(ConnectionInterface $connection, Protocol $protocol)
    {
        // TODO: Implement onClose() method.
    }
}
