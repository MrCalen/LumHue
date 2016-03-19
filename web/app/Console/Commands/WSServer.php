<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\WebSockets\Protocol;
use App\WebSockets\Strategy\ChatStrategy;

class WSServer extends Command
{
    protected $name = 'ws:serve';
    protected $description = 'Start Websockets handlers.';

    private $hueChatPort = '9090';

    public function __construct()
    {
        parent::__construct();
    }

    public function startHueChat()
    {
        $this->info("Starting WebSockets on port " . $this->hueChatPort);

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Protocol(new ChatStrategy())
                )
            ),
            $this->hueChatPort,
            '0.0.0.0'
        );
        $server->run();
    }

    public function fire()
    {
        $this->startHueChat();
    }
}
