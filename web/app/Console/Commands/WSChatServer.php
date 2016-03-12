<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Models\Chat;

class WSChatServer extends Command {

  protected $name = 'chat:serve';
  protected $description = 'Start chat server.';

  public function __construct()
  {
    parent::__construct();
  }

  public function fire()
  {
    $this->info("Starting chat web socket server on port " . '9090');

    $server = IoServer::factory(
      new HttpServer(
        new WsServer(
          new Chat()
        )
      ),
      '9090',
      '0.0.0.0'
    );
    $server->run();
  }
}
