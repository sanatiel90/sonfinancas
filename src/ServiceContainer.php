<?php

namespace SONFin;

use Xtreamwayz\Pimple\Container;  //importando pimple para uso de service container

class ServiceContainer implements ServiceContainerInterface{
	
	//container do pimple que armazena servico 
	private $container;


	public function __construct()
	{
		$this->container = new Container();
	}

	//add um novo servico ao container
	public function add(string $name, $service){
		$this->container[$name] = $service; 
	} 
	
	//add um novo servico ao container, mas com retardo
	public function addLazy(string $name, callable $callable){
		$this->container[$name] = $this->container->factory($callable);
	}
	
	//retorna o servico contido no container com base no nome passado
	public function get(string $name){
		return $this->container->get($name);
	}

	//retorna um boolean caso o servico  exista no container
	public function has(string $name){
		return $this->container->has($name);
	}

}