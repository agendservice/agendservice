<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class loadBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adece:loadBackup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar informações do backup para esse sistema';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        new \App\Services\LoadService();
    }
}
