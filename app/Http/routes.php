<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
// $app->configure('cors');
$app->get('/', function () use ($app) {
    return $app->welcome();
});

$app->get('api/drop2json/', "DropboxController@noUrl");
$app->get('api/drop2json/{url}', "DropboxController@drop2json");

// $app->group(['prefix' => 'api'], function ($app) {
//     $app->get('drop2json', "DropboxController@drop2json");
// 	// $app->get('drop2json/{url}', "DropboxController@drop2json");
// });