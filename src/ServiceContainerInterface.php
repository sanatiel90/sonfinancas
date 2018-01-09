<?php
declare(strict_types = 1);  //comando necessario para php 7 usar recurso de tipagem de dados em parametros de metodos

namespace SONFin; 

//interface resposavel por criar e disponibilizar servicos, q serao utilizados em diversas partes da aplicacao
interface ServiceContainerInterface {
	//metodos dos servicos q serao disponibilizados pela interface

	public function add(string $name, $service); //tipo string obrigatorio apenas para param $name; param $service pode receber qq tipo de dado ou classe

	public function addLazy(string $name, callable $callable);

	public function get(string $name);

	public function has(string $name);




}

