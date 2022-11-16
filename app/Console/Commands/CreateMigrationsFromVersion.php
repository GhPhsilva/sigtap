<?php

namespace App\Console\Commands;

use App\Models\Version;
use Illuminate\Console\Command;

class CreateMigrationsFromVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sigtap:create:migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $version = Version::with(['tables.columns'])->latest()->take(2)->get();
        if($version->count() > 1) {
            return;
        }

        $this->generateMigrationsFromVersion($version->first());
    }

    private function generateMigrationsFromVersion(Version $version): void
    {
        $version->tables()->each(function ($table) {
           $this->call('make:migration:schema', [
               'name' => 'create_'.$table->name.'_table',
               '--schema' => $table->generateSchemaString(),
               //'--model' => true,
           ]);
        });
    }
}
