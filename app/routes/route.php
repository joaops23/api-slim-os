<?php
namespace App;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Controllers\Controller;
use Slim\Routing\RouteCollectorProxy;
use Slim\Views\Twig;
use Helper\Helpers;


$app->get('/', [Controller::class, 'welcome']);

// Grupo de ordens de Serviço
$app->group('/ordens', function(RouteCollectorProxy $group){
    $group->get('/cadastro', [Controller::class, 'cadastro']);

    
    $group->post('/insert', [Controller::class, 'inserir'])->setName("insert");
    
});


$app->group('/utils', function(RouteCollectorProxy $group){
    $group->get('/login', function($request, $response, $args){
        $view = Twig::fromRequest($request);
        return $view->render($response, 'js/login.js');
    })->setName("login.js");
});

$app->run();