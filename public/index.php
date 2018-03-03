<?php

use Psr\Http\Message\RequestInterface; 
use Psr\Http\Message\ServerRequestInterface;
use SONFin\Application;
use SONFin\Plugins\RoutePlugin;
use SONFin\Plugins\ViewPlugin;
use SONFin\Plugins\DbPlugin;
use SONFin\Plugins\AuthPlugin;
use SONFin\ServiceContainer;
use Zend\Diactoros\Response;
use SONFin\Models\CategoryCost; 


require_once __DIR__ . '/../vendor/autoload.php';  //carregando dependencias

$serviceContainer = new ServiceContainer(); //instanciando container de servicos 

$app = new Application($serviceContainer); //instanciando  application


//'plugando'/incorporando o plugin de rotas à application
$app->plugin(new RoutePlugin()); 
$app->plugin(new ViewPlugin());
$app->plugin(new DbPlugin());
$app->plugin(new AuthPlugin());




//usando met get() para criar uma rota para ser acessada via GET; parametros serao armazenados numa instancia de ServiceRequestInterface
//A function(ServerRequestInterface $request) é a ação da rota, presente na linha $callable = $route->handler; mét. start da class Application
$app->get('/home/{name}', function(ServerRequestInterface $request)
{
	$response = new Response(); //classe Zend\Diactoros, instancia para resposta
	$response->getBody()->write('Resposta da req'); //capturando o BODY da requisicao e escrevendo
	return $response; //retornando resposta

	//echo $request->getAttribute('name'); //mostrando param enviado na requisicao
});


															//para receber o contexto da app
$app->get('/teste/rota{name}',function(ServerRequestInterface $request) use($app)
{
	
	$view = $app->service('view.renderer');	//pegando servico de renderizador
	//acessando o metodo de renderizar, informando qual template é pra mostrar e qual contexto(variaveis serão passadas)
	return $view->render('test.html.twig', ['name' => $request->getAttribute('name')] ); 

});

//importando controller referent as categorias; precisa ser importado depois da criacao da instancia de $app, pois esta instancia sera usada la no controller
require_once __DIR__ . '/../src/controllers/category-costs.php'; 
require_once __DIR__ . '/../src/controllers/users.php'; 
require_once __DIR__ . '/../src/controllers/auth.php'; 


$app->start(); //metodo q inicia a app