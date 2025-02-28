<?php

namespace App\Console\Commands;

use Blazer\Core\DevServer;

class ServeCommand
{
    public function run()
    {
        $server = new DevServer();
        $server->start(8000);
    }
} 