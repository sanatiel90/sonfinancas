<?php
declare(strict_types = 1);

namespace SONFin\Plugins;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface; 
use SONFin\ServiceContainerInterface;
use Zend\Diactoros\ServerRequestFactory; //lib zendframekwork zend-diactoros
use SONFin\Auth\Auth;


/**
* class plugin q gerencia a autenticacao da aplicacao
*/
class AuthPlugin implements PluginInterface
{
	//metodo para registrar/adicionar novos servicos (no caso da class RoutePlugin, serao servidos de rotas)
	public function register(ServiceContainerInterface $container){

			$container->addLazy('auth', function(ContainerInterface $container){
			return new Auth(); 
	
		});

		
	}


	


}

