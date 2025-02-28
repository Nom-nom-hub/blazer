<?php

namespace Blazer\Core\Console\Commands;

class NewCommand
{
    public function run($args)
    {
        if (empty($args[0])) {
            echo "Please provide a project name\n";
            echo "Usage: blazer new my-project\n";
            return;
        }

        $projectName = $args[0];
        echo "Creating new Blazer project: {$projectName}\n";

        // Create new project using Composer
        exec("composer create-project --prefer-dist blazer/framework {$projectName}");

        echo "\n✨ Project created successfully!\n\n";
        echo "Get started with:\n";
        echo "  cd {$projectName}\n";
        echo "  php blazer serve\n\n";
    }
} 