<?php

namespace Blazer\Core\Console\Commands;

class MigrateCommand {
    public function run($args) {
        echo "Running migrations...\n";
        $files = glob(ROOT_DIR . '/database/migrations/*.php');
        foreach ($files as $file) {
            require_once $file;
            $class = 'Migration_' . basename($file, '.php');
            $migration = new $class();
            $migration->up();
        }
    }
} 