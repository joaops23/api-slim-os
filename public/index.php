<?php 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';
define("_APP", dirname(__FILE__). "/../app");

$app = AppFactory::create();

//instanciando o twig e apontando o dir de templates
$twig = Twig::create('../app/views', ['cache' => false]);

$app->add(TwigMiddleware::create($app, $twig));

require_once(_APP . "/Database/OrdServico.php");
require_once(_APP . "/Controllers/Controller.php");
require_once(_APP . "/helpers/helper.php");
require_once(_APP . "/routes/route.php");

?>