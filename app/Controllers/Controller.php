<?php
namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Model\OS;
use Slim\Views\Twig;

$banco = new OS();

require __DIR__ . '/../../vendor/autoload.php';

class Controller {
    public function welcome(Request $request, Response $response, $args): Response {
        global $banco;
        //recupera a lista de ordens de serviço já cadastradas no banco
        $lista = json_decode($banco::select("ordens"), true);
        $view = Twig::fromRequest($request);
        return $view->render($response, 'index.html', [
            'lista' => $lista
        ]);
        //$response->getBody()->write($lista);
    }
}

// Construir classe de ordem de serviço {inclusão, exclusão, listagem, alteração} usando o model respectivo