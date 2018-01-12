<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use SONFin\Application;
use SONFin\Plugins\RoutePlugin;
use SONFin\ServiceContainer;

require_once __DIR__ . '/../vendor/autoload.php';  //carregando dependencias



$serviceContainer = new ServiceContainer(); //instanciando container de servicos 

$app = new Application($serviceContainer); //instanciando  application


//'plugando'/incorporando o plugin de rotas à application
$app->plugin(new RoutePlugin()); 

//usando met get() para criar uma rota para ser acessada via GET
$app->get('/home', function(){
	echo "Hello World!!";
});


$app->start(); //metodo q inicia a app