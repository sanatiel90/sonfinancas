<?php

use Psr\Http\Message\RequestInterface; 
use Psr\Http\Message\ServerRequestInterface;
use SONFin\ServiceContainer;
use Zend\Diactoros\Response;


//controller para users , contem as rotas e acoes a serem tomadas nessas rotas referentes as users

//rota para listar users; a; nomeada como users.list
$app->get('/users', function() use($app)
{
	$view = $app->service('view.renderer');

	$repository = $app->service('user.repository');

	$users = $repository->all();	//listando todas users

   //passando users para o contexto, para q possam ser acessadas na view pela tag do twig {% %}
	return $view->render('users/list.html.twig', [
		'users' => $users
	]); 

}, 'users.list');

//rota q direciona para form de nova user; nomeada como users.new
$app->get('/users/new', function(ServerRequestInterface $request) use($app)
{
	$view = $app->service('view.renderer');
	return $view->render('users/create.html.twig'); 

}, 'users.new');

//rota para salvar nova user;  nomeada como users.store
$app->post('/users/store', function(ServerRequestInterface $request) use($app)
{
	$repository = $app->service('user.repository');

	$data = $request->getParsedBody();	//recebendo os dados enviados pelo form	

	$repository->create($data); //salvando os dados no bd
	//atraves da classe RedirectResponse, redirecionando para outra rota (neste caso, a de listagem de users)
	return $app->route('users.list'); 		
}, 'users.store');


//rota q direciona para form de edicao de user; nomeada como users.edit
$app->get('/users/{id}/edit', function(ServerRequestInterface $request) use($app)
{
	$view = $app->service('view.renderer');

	$repository = $app->service('user.repository');

	
	$user = $repository->find($request->getAttribute('id')); //pegando a categ a ser editada de acordo com o id repassado
	
	return $view->render('users/edit.html.twig',['user' => $user]); 

}, 'users.edit');

//rota para salvar user editada; nomeada como users.update
$app->post('/users/{id}/update', function(ServerRequestInterface $request) use($app)
{
	$repository = $app->service('user.repository');

	/*$userCostModel = new userCost();
	$user = $userCostModel->findOrFail($request->getAttribute('id')); //pegando a categ a ser editada de acordo com o id repassado
	$data = $request->getParsedBody(); //pegando os dados enviados pelo form de edicao, serao os dados a serem atualizados
	$user->fill($data); //preenchendo na user a ser editada os novos dados enviados
	$user->save();*/
	$data = $request->getParsedBody(); 

	$repository->update($request->getAttribute('id'), $data);
	
	return $app->route('users.list');

}, 'users.update');


//rota q direciona para tela de detalhamento da user, onde é possivel deletar; nomeada como users.show
$app->get('/users/{id}/show', function(ServerRequestInterface $request) use($app)
{
	$view = $app->service('view.renderer');
	$repository = $app->service('user.repository');


	$user = $repository->find($request->getAttribute('id')); //pegando a categ a ser editada de acordo com o id repassado
	
	return $view->render('users/show.html.twig',['user' => $user]); 

}, 'users.show');


//rota para deletar user; nomeada como users.delete
$app->get('/users/{id}/delete', function(ServerRequestInterface $request) use($app)
{
	$repository = $app->service('user.repository');

	
	$repository->delete($request->getAttribute('id'));
	
	return $app->route('users.list');

}, 'users.delete');