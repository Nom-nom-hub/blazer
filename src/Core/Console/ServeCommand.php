<?php

namespace Blazer\Core\Console;

class ServeCommand
{
    public function run($args)
    {
        $host = $args[0] ?? 'localhost';
        $port = $args[1] ?? '8000';
        
        echo "Starting development server at http://{$host}:{$port}\n";
        echo "Press Ctrl+C to stop\n\n";
        
        // Set environment variables
        putenv('APP_DEBUG=true');
        putenv('APP_ENV=local');
        
        // Start PHP's built-in server with custom router
        $command = sprintf(
            '%s -S %s:%s -t public/ public/router.php 2>/dev/null',
            PHP_BINARY,
            $host,
            $port
        );
        
        passthru($command);
    }
} 