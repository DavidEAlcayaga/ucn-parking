<?php

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


require_once 'Ice.php';
// Ruta relativa del domain.php (resultado de compilar el domain.ice)
// Asume que el domain.php esta en la siguiente ubicación /ucn-parking/web/domain.php
require_once '../domain.php';

Route::get('/', function () {

    return view('welcome');
});

Route::get('/test','ConnectionController@connection');
Route::post('/receive','ConnectionController@receive');

/**
 * Test connection with the backend
 */
Route::get('/delay', function(){
    // Initialize Ice communicator
    // must have declared -> require_once 'Ice.php'
    $communicator = Ice\Initialize();

    // Initialize proxy
    // Sistema proxy -> "Sistema:tcp -z -t 15000 -p 3000"
    // Contratos proxy -> "Contratos:tcp -z -t 15000 -p 3000"
    $sistema_proxy = $communicator->StringToProxy("Sistema:tcp -z -t 15000 -p 3000");

    // Creates interface
    $sistema = \model\SistemaPrxHelper::uncheckedCast($sistema_proxy);

    $client_time = (int) round(microtime(true)*1000);
    echo("Client time: ".$client_time);

    // Calls interface method
    $delay = $sistema->getDelay($client_time);
    echo "<br>";
    echo("Delay: ".$delay);


});
