<?php

namespace App\Console\Commands;

use App\Models\Column;
use App\Models\Table;
use App\Models\Version;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ConsumeLayoutFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sigtap:consume:layout {competencia : Competencia da sigtap}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse layout file for sigtap importation';

    private const END_OF_FILE = '@';
    private const LAYOUT_FILE_NAME =  'layout.txt';


    private function getFileArray(string $competencia): array
    {
        $file = File::get(storage_path('app/public/'.$competencia.'/'.self::LAYOUT_FILE_NAME));
        $file = str_replace(array('', "\r"), '', $file);
        $array =  array_values(array_filter(explode("\n", $file), static fn ($item) => $item !== ''));
        $array[] = self::END_OF_FILE;
        return $array;
    }

    private function getTableNamesArray(array $fileArray): array
    {
        return array_values(
                array_filter(
                    $fileArray,
                    static fn ($element) => str_contains($element,'tb_') || str_contains($element,'rl_')
                )
        );
    }

    private function getFinalLayoutArray(array $tableNamesArray, array $fileArray): array
    {
        $finalLayoutArray = [];
        foreach($tableNamesArray as $key=>$table) {
            $init = array_search($table,$fileArray,true);
            $end = array_search($tableNamesArray[$key + 1] ?? '@',$fileArray,true);
            $columnsArray = array_slice($fileArray,$init,$end-$init ,true);

            array_shift($columnsArray);
            array_shift($columnsArray);
            $finalLayoutArray[$table]['columns'] = $columnsArray;
            $finalLayoutArray[$table]['columnNames'] = $this->getColumnNames($columnsArray);
            $finalLayoutArray[$table]['table'] = $table;
        }
        return $finalLayoutArray;
    }


    /**
     * Execute the console command.
     *
     * @return int
     * @throws \JsonException
     */
    public function handle(): int
    {
        $competencia = $this->argument('competencia');
        $fileArray = $this->getFileArray($competencia);
        $tableNamesArray = $this->getTableNamesArray($fileArray);
        $finalLayoutArray = $this->getFinalLayoutArray($tableNamesArray,$fileArray);
        $this->createVersion($competencia,$finalLayoutArray);

        return Command::SUCCESS;
    }

    private function getColumnNames(array $columnsArray): array
    {
        $names = [];
        foreach($columnsArray as $columnString) {
            $names[] = explode(',',$columnString)[0];
        }
        return $names;
    }

    /**
     * @throws \JsonException
     */
    private function createVersion(string $versionName, array $finalLayoutArray): void
    {
        $version = new Version();
        $version->name = $versionName;
        $version->save();

        foreach ($finalLayoutArray as $tableArray) {
            $table = new Table();
            $table->version_id = $version->id;
            $table->name = $tableArray['table'];
            $table->column_names = json_encode($tableArray['columnNames'], JSON_THROW_ON_ERROR);
            $table->data_file = 'app/public/'.$versionName.'/'.$tableArray['table'].'.txt';
            $table->save();

            $fieldNames = [
                'version_id',
                'table_id',
                'name',
                'import_length',
                'import_start',
                'import_end',
                'type',
            ];
            foreach ($tableArray['columns'] as $columnArray) {
                $insertArray = explode(',',$columnArray);
                array_unshift($insertArray,$table->id);
                array_unshift($insertArray,$version->id);
                $column = new Column();
                foreach (array_combine($fieldNames,$insertArray) as $field=>$value) {
                    $column->{$field} = $value;
                }
                $column->save();
            }
        }
    }
}
