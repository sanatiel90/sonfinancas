<?php

use Psr\Http\Message\RequestInterface; 
use Psr\Http\Message\ServerRequestInterface;
use SONFin\ServiceContainer;
use Zend\Diactoros\Response;


//controller para categorias de custo, contem as rotas e acoes a serem tomadas nessas rotas referentes as categorias

//rota para listar categorias; a; nomeada como category-costs.list
$app->get('/login', function(ServerRequestInterface $request) use($app)
{
	$view = $app->service('view.renderer');
	return $view->render('auth/login.html.twig'); 

}, 'auth.show_login_form');

//rota q direciona para form de nova categoria; nomeada como category-costs.new
$app->post('/login', function(ServerRequestInterface $request) use($app)
{
	

}, 'auth.login');




