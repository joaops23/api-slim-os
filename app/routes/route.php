<?php
namespace App;

use Controllers\Controller;


$app->get('/', [Controller::class, 'welcome']);

// Grupo de ordens de Serviço
$app->group('/ordens', function() use($app){
    $app->get('', function($id){
        
    });
    $app->post('', function($id){});
    $app->put('', function($id){});
    $app->delete('', function($id){});
});

$app->run();