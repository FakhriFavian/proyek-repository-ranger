<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CreateMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:migrationx {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $names;

    protected $file;

    public function handle(Filesystem $file)
    {
        $this->file = $file;

        $this->names = explode(',',trim($this->argument('table')));

        foreach ($this->names as $key => $value) {
            $stub = resource_path('stubs/migration.create.stub');
            $mig = base_path('database/migrations/'.date("Y_m_d_His")."_create_".$value."_table.php");
            $this->createFile($stub, $mig, ['//table//' => $value]);

            $this->info("Created Migration: ". $mig);
        }

    }

    public function createFile($from, $to, $replaces = [])
    {
        $stub = $this->file->get($from);
        foreach ($replaces as $r => $p)
        {
            $stub = str_replace($r, $p, $stub);
        }

        $this->file->put($to, $stub);
    }
}
