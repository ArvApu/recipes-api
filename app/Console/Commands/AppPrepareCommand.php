<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppPrepareCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string $signature
     */
    protected $signature = 'app:prepare';

    /**
     * The console command description.
     *
     * @var string $description
     */
    protected $description = 'Execute needed commands to start program.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('migrate', ['--force' => true]);
        $this->call('storage:link');
    }
}
