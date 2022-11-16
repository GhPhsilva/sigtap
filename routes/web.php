<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // transformar tudo em comando !
    // deixar dinamico
    $file = File::get(storage_path('app/public/201809/layout.txt'));
    $file = str_replace(array('', "\r"), '', $file);
    $array =  array_values(array_filter(explode("\n", $file), static fn ($item) => $item !== ''));
    $array[] = '@';
    $tables = array_values(array_filter($array,static fn ($element) => str_contains($element,'tb_') || str_contains($element,'rl_')));
    foreach($tables as $key=>$table) {
        $init = array_search($table,$array);
        $end = array_search(isset($tables[$key+1]) ? $tables[$key+1] : '@' ,$array);
        $tableArray = array_slice($array,$init,$end-$init ,true);
        array_shift($tableArray);
        array_shift($tableArray);
        $new[$table] = $tableArray;
    }

    foreach($new as $name => $teste) {
        $final[$name]['tabela'] = $name;
        $final[$name]['data'] = storage_path('app/public/201809/'.$name.'.txt');
        foreach ($teste as $string) {
            $final[$name]['colunas']= array_combine(['nome','tamanho','inicio','fim','tipo'],explode(',',$string));
        }
    }

    // salvar estrutura no BD
    // a partir do BD ir gerando scripts sql e salvando no BD
    // consumir todos os scripts sql
});

