<?php
declare(strict_types = 1);
namespace SONFin;

use SONFin\Plugins\PluginInterface;
use Psr\Http\Message\RequestInterface; //lib Zend\Diactoros que implementa a PSR 7 com padrões para uso do HTTP
use Psr\Http\Message\ResponseInterface; //lib Zend\Diactoros que implementa a PSR 7 com padrões para uso do HTTP
use SONFin\ServiceContainerInterface;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\Response\RedirectResponse;
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


	/*metodo para 'plugar'/adicionar um plugin nessa classe; o metodo register do obj plugin vai receber como parametro 
	  a instancia de serviceContainer da class Application e vai adicionar os servicos no container*/ 
	public function plugin(PluginInterface $plugin): void{
		$plugin->register($this->serviceContainer);
	}

	//met. para acessar uma rota (caminho) informada via GET e executar uma acao, tambem pode ser nomeada; retorna instancia da propria class Application 
	public function get($path, $action, $name = null): Application{
		$routing = $this->service('routing'); //acessando o servico 'routing' (q é o servico $map do RoutePlugin) para mapear/registrar as rotas
		$routing->get($name,$path,$action); //metodo get do servico Map (q é da lib AureaRouter e esta configurada com nome 'routing' ) 
		return $this;  //retorna instancia de Application

	}


	//met. para acessar uma rota (caminho) informada via POST e executar uma acao, tambem pode ser nomeada; retorna instancia da propria class Application 
	public function post($path, $action, $name = null): Application{
		$routing = $this->service('routing'); 
		$routing->post($name,$path,$action); 
		return $this; 

	}



	//met. para redirecionar para uma determinada rota 
	public function redirect($path){	

		return new RedirectResponse($path); 

	}

	//met. para redirecionar com base numa determinada rota nomeada 
	public function route(string $name, array $params = []){	
		$generator = $this->service('routing.generator');	//criando servico para nomear rotas
		$path = $generator->generate($name, $params);
		return $this->redirect($path);
	}
	 

	

	//startando app a partir da rota acessada
	public function start()
	{
		$route = $this->service('route');	//pegando rota acessada atraves do servico 'route' (matcher + request)
		/** @var ServerRequestInterface $request */
		$request = $this->service(RequestInterface::class); //criando servico de requisicao atraves do servico RequestInterface; ele armazenara os parametros

		//se rota digitada nao existir
		if(!$route){
			echo "Page not found";
			exit;
		}

		//percorrendo os atributos(parametros) informados na rota e atribuindo-os à $request
		foreach($route->attributes as $key => $value){
			$request = $request->withAttribute($key,$value);
		}

		$callable = $route->handler; //handler: pegando a acao gerada pelo servico route(a acao será uma funcao, q sera armazen na var $callable)
		 
		//colocando na resposta da requisicao a funcao/acao q tem dentro da var $callable, q é a ação da rota, juntamente com os parametros da request 
		$response = $callable($request); 

		$this->emitResponse($response);	//emitindo a resposta da requisicao
	}

	//metodo para emitir respostas da requisicao; param: instancia de ResponseInterface, q é a classe padrão da PSR 7 para respostas HTTP
	public function emitResponse(ResponseInterface $response)
	{
		$emitter = new SapiEmitter(); //class da lib Zend\Diactoros: emissor baseado na API de Servidor do PHP (Sapi : Server API)
		$emitter->emit($response);
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