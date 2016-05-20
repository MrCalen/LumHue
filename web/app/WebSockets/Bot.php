<?php


namespace App\WebSockets;


class Bot
{
    private static $instance;
    private function __construct() {}

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new Bot();
        }
        return self::$instance;
    }

    public function handleMessage($message) {
        $messageParts = explode(' ', $message);
        $command = $messageParts[0];
        return $this->handleCommand($command, array_slice($messageParts, 1));
    }

    private function handleCommand($command, $args) {
        switch ($command) {
            case '/help':
                return '<div>
                            <h4>Help:</h4>
                            <ul>
                                <li>/light light_name color: applies the new color to the lamp</li>
                                <li>/help: prints this help message and exits</li>
                            </ul>
                        </div>';
                break;
        }
        return $command;
    }

    public function onConnect($clientName) {
        return [
            "type" => 'message',
            "content" => "
                <p><b>Bienvenue ${clientName}</b></p>
                <p>Pour utiliser notre service:
                    <ul>
                        <li><pre>Allume la lampe une</pre></li>
                        <li><pre>Mets la première lampe en rouge</pre></li>
                    </ul>
                </p>
                ",
            'date' => date('l jS \of F Y h:i:s A'),
        ];
    }

}