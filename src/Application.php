<?php
declare(strict_types = 1);
namespace SONFin;

use SONFin\Plugins\PluginInterface;
/**
* esta classe irá administrar a aplicacao
  - irá usar o ServiceContainer, q por sua vez implementa a interface ServiceContainerInterface
  - Plugins para add novos recursos de forma separada
*/
class Application {

	//instancia de serviceContainer, q pode usar os metodos da interface ServiceContainerInterface
	private $serviceContainer;
	
	//injetando dependencia
	function __construct(ServiceContainerInterface $serviceContainer)
	{
		$this->serviceContainer = $serviceContainer;
	}

	//utilizando o obj de serviceContainer para retorna o servico
	public function service($name){
		return $this->serviceContainer->get($name);
	}

	/*utilizando o obj de serviceContainer para add um servico, lazy ou normal dependendo do servico informado;
	 php 7: void para indicar q metodo nao tem retorno*/	
	 public function add($name, $service): void{
		if(is_callable($service)){
			$this->serviceContainer->addLazy($name, $service);
		} else{
			$this->serviceContainer->add($name, $service);
		}
	}


	/*metodo para registrar um plugin nessa classe, o metodo register do obj plugin vai receber como parametro 
	  a instancia de serviceContainer da class Application*/ 
	public function plugin(PluginInterface $plugin): void{
		$plugin->register($this->serviceContainer);
	}

}


/*
injecao de depen

ao inves de instanciar objetos dentro do metodo da classe, como por exemplo

function __construct()
	{
		$this->serviceContainer = new ServiceContainer();
	}


, o metodo ja vai receber o obj instanciado, para apenas usa-lo, evitando assim acoplamento

function __construct(ServiceContainerInterface $serviceContainer)
	{
		$this->serviceContainer = $serviceContainer;
	}

*/