<?php
namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Model\OS;
use Slim\Views\Twig;
use Helper\Helpers;

$banco = new OS();

$helper = new Helpers();

require __DIR__ . '/../../vendor/autoload.php';

class Controller {
    public function welcome(Request $request, Response $response, $args): Response {
        global $banco;
        //recupera a lista de ordens de serviço já cadastradas no banco
        $lista = json_decode($banco::select("ordens"), true);
        $view = Twig::fromRequest($request);
        return $view->render($response, 'lista.html', [
            'lista' => $lista,
            'titulo' => "Ordens de serviço cadastradas"
        ]);
        //$response->getBody()->write($lista);
    }

    public function cadastro(Request $request, Response $response, $args): Response {
        global $banco;

        $lista = json_decode($banco::select("ordens"), true);
        $view = Twig::fromRequest($request);
        return $view->render($response, 'cadastro.html', [
            'titulo' => "Inclusão de Ordens de Serviço"
        ]);
    }

    
    
    public function inserir(Request $request, Response $response, $args): Response {
        global $banco;
        $dados = $request->getBody()->getContents();

        $view = Twig::fromRequest($request);
        $retorno = json_decode($banco->inserir($dados),true);
        $request->getBody()->write($retorno);
        return $helper->jsonResponse(true, $retorno['message'], $retorno);
    }
    
    
}

// Construir classe de ordem de serviço {inclusão, exclusão, listagem, alteração} usando o model respectivo