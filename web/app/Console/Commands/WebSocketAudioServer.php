<?php

namespace App\Console\Commands;

use App\WebSockets\Strategy\AudioStrategy;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\WebSockets\Protocol;

use App\Helpers\SpeechAPI\SpeechApiHelper;


class WebSocketAudioServer extends Command
{
    protected $name = 'ws:audioserve';
    protected $description = 'Start Websockets handlers.';

    private $audioPort = "9091";

    public function __construct()
    {
        parent::__construct();
    }

    public function startAudioHue()
    {
        $this->info('Starting Audio Hue on port ' . $this->audioPort);

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Protocol(new AudioStrategy())
                )
            ),
            $this->audioPort,
            "0.0.0.0"
        );
        $server->run();
    }

    public function fire()
    {
        $this->startAudioHue();
    }
}
