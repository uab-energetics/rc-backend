<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunBootstrapped extends Command
{
    protected $signature = 'custom:run {file}';

    protected $description = 'Executes a custom php script within the Laravel environment';

    public function handle()
    {
        $file = $this->argument('file');
        require_once $file;
    }
}
