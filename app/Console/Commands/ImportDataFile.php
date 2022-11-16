<?php

namespace App\Console\Commands;

use App\Models\Column;
use App\Models\Table;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportDataFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sigtap:import:data-file {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        if($name) {
            $this->importSingleTableDataFile($name);
        }
        else {
            $this->importAllTableDataFiles();
        }

        return Command::SUCCESS;
    }

    private function importSingleTableDataFile($name): void
    {
        $table = Table::where('name',$name)->where('imported',false)->firstOrFail();
        $file = File::get(storage_path($table->data_file));
        $collection =  collect(array_values(array_filter(explode("\r\n", $file), static fn ($item) => $item !== '')));

        $collection->map(fn (string $string) =>
             $table->columns->mapWithKeys(fn (Column $column) =>
                array_merge(
                    [
                        'version_id' => $table->version_id,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'updated_at' => now()->format('Y-m-d H:i:s')
                    ],
                    [$column->name => $this->getValueFromString($column,utf8_encode($string))]
                )
            )
        )
        ->chunk(1000)
        ->each(fn ($chunk) => DB::table($table->name)->insert($chunk->toArray()));
        $table->imported = true;
        $table->save();
    }

    private function importAllTableDataFiles(): void
    {
        $table = Table::where('imported',false)->firstOrFail();
    }

    private function getValueFromString(Column $column, string $string): ?string
    {
        $rawValue = Str::substr($string,$column->import_start-1,$column->import_length);
            return empty(trim($rawValue)) ? null : trim($rawValue);
    }
}
