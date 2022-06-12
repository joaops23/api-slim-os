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
        $body = $request->getBody()->getContents();

        $view = Twig::fromRequest($request);

        // Tratando os dados recebidos pelo body e padronizando em um array json
        $dado_brt = explode('&', $body);
        $dados = array();
        foreach($dado_brt as $dado){
            $dado = explode("=",$dado);
            $dados[$dado[0]] = $dado[1];
        }
        
        $dados = json_encode($dados);
        $retorno = $banco->inserir($dados);
        $response->getBody()->write($retorno);
        return $response;
    }
    
    
}

// Construir classe de ordem de serviço {inclusão, exclusão, listagem, alteração} usando o model respectivo