<?php
declare(strict_types = 1);

namespace SONFin\Plugins;


use SONFin\ServiceContainerInterface;
use Illuminate\Database\Capsule\Manager as Capsule;


/**
* class plugin q gerencia as config do db da aplicacao, utilizando a lib Eloquent
*/
class DbPlugin implements PluginInterface
{
	//metodo para registrar/adicionar novos servicos (no caso da class ViewPlugin, serao servidos para renderizar os templates da app)
	public function register(ServiceContainerInterface $container){
		
		//no caso do Eloquent, nao precisa registrar um servico, ele atua de forma global na app
		$capsule = new Capsule(); //obj Capsule cria conexao e inicia o Eloquent
		$config = include __DIR__ . '/../../config/db.php'; //recuperando dados de conexao do bd
		$capsule->addConnection($config['development']); //criando a conexao
		$capsule->bootEloquent(); //iniciando Eloquent
		
	}


	
}

