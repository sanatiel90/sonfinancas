<?php

use Psr\Http\Message\RequestInterface; 
use Psr\Http\Message\ServerRequestInterface;
use SONFin\ServiceContainer;
use Zend\Diactoros\Response;
use SONFin\Models\CategoryCost; 

//controller para categorias de custo, contem as rotas e acoes a serem tomadas nessas rotas referentes as categorias

//rota para listar categorias; a; nomeada como category-costs.list
$app->get('/category-costs', function(ServerRequestInterface $request) use($app)
{
	$view = $app->service('view.renderer');

	$categoryCostModel = new CategoryCost();	//criando model de CategoryCost para acessar dados do bd
	$categories = $categoryCostModel->all();	//listando todas categorias

   //passando categorias para o contexto, para q possam ser acessadas na view pela tag do twig {% %}
	return $view->render('category-costs/list.html.twig', [
		'categories' => $categories
	]); 

}, 'category-costs.list');

//rota q direciona para form de nova categoria; nomeada como category-costs.new
$app->get('/category-costs/new', function(ServerRequestInterface $request) use($app)
{
	$view = $app->service('view.renderer');
	return $view->render('category-costs/create.html.twig'); 

}, 'category-costs.new');

//rota para salvar nova categoria; a; nomeada como category-costs.store
$app->post('/category-costs/store', function(ServerRequestInterface $request) use($app)
{
	$data = $request->getParsedBody();	//recebendo os dados enviados pelo form
	$categoryCostModel = new CategoryCost();
	$categoryCostModel->create($data); //salvando os dados no bd
	//atraves da classe RedirectResponse, redirecionando para outra rota (neste caso, a de listagem de categorias)
	return $app->route('category-costs.list'); 		
}, 'category-costs.store');


//rota q direciona para form de edicao de categoria; nomeada como category-costs.edit
$app->get('/category-costs/{id}/edit', function(ServerRequestInterface $request) use($app)
{
	$view = $app->service('view.renderer');

	$categoryCostModel = new CategoryCost();
	$category = $categoryCostModel->findOrFail($request->getAttribute('id')); //pegando a categ a ser editada de acordo com o id repassado
	
	return $view->render('category-costs/edit.html.twig',['category' => $category]); 

}, 'category-costs.edit');

//rota para salvar categoria editada; nomeada como category-costs.update
$app->post('/category-costs/{id}/update', function(ServerRequestInterface $request) use($app)
{

	$categoryCostModel = new CategoryCost();
	$category = $categoryCostModel->findOrFail($request->getAttribute('id')); //pegando a categ a ser editada de acordo com o id repassado
	$data = $request->getParsedBody(); //pegando os dados enviados pelo form de edicao, serao os dados a serem atualizados
	$category->fill($data); //preenchendo na categoria a ser editada os novos dados enviados
	$category->save();
	
	return $app->route('category-costs.list');

}, 'category-costs.update');


//rota q direciona para tela de detalhamento da categoria, onde é possivel deletar; nomeada como category-costs.show
$app->get('/category-costs/{id}/show', function(ServerRequestInterface $request) use($app)
{
	$view = $app->service('view.renderer');

	$categoryCostModel = new CategoryCost();
	$category = $categoryCostModel->findOrFail($request->getAttribute('id')); //pegando a categ a ser editada de acordo com o id repassado
	
	return $view->render('category-costs/show.html.twig',['category' => $category]); 

}, 'category-costs.show');


//rota para deletar categoria; nomeada como category-costs.delete
$app->get('/category-costs/{id}/update', function(ServerRequestInterface $request) use($app)
{

	$categoryCostModel = new CategoryCost();
	$category = $categoryCostModel->findOrFail($request->getAttribute('id')); //pegando a categ a ser editada de acordo com o id repassado
	
	$category->delete();
	
	return $app->route('category-costs.list');

}, 'category-costs.delete');