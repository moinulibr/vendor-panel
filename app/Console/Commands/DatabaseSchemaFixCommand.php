<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseSchemaFixCommand extends Command
{
    protected $signature = 'db:schema-fix';

    protected $description = 'Database Schema Fix';

    public function handle(): int
    {
        $this->info('Schema Fix Started');

        require database_path('schema-fixes/run.php');

        $this->info('Schema Fix Completed');

        return self::SUCCESS;
    }
}
