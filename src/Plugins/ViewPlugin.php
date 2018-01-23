<?php
declare(strict_types = 1);

namespace SONFin\Plugins;


use Interop\Container\ContainerInterface;
use SONFin\View\ViewRenderer;
use SONFin\ServiceContainerInterface;


/**
* class plugin q gerencia as views da aplicacao, utilizando a lib Twig
*/
class ViewPlugin implements PluginInterface
{
	//metodo para registrar/adicionar novos servicos (no caso da class ViewPlugin, serao servidos para renderizar os templates da app)
	public function register(ServiceContainerInterface $container){
		
		//servico lazy para renderizar os templates da app 
		$container->addLazy('twig', function(ContainerInterface $container){
			//criando carregador de templates (Twig_Loader_FileSystem:class para criar o carregador); param: pasta onde os templates estão armazenados
			$loader = new \Twig_Loader_FileSystem(__DIR__ . '/../../templates'); 

			$twig = new \Twig_Environment($loader); // Twig_Environment: ambiente do Twig

			$generator = $container->get('routing.generator');	//criando servico para nomear rotas
			
			//add uma funcao ao twig, chamada 'route'; ela vai usar o servico generator para criar rotas nomeadas, informando o nome da rota e eventuais param
			$twig->addFunction(new \Twig_SimpleFunction('route', function(string $name, 	array $params = []) use($generator){
				return $generator->generate($name,$params);

			}));

			return $twig;
 
		});


		//servico para pegar o twig e usar nas rotas 
		$container->addLazy('view.renderer', function(ContainerInterface $container){
			$twigEnvironment = $container->get('twig'); 

			return new ViewRenderer($twigEnvironment);
		});
		
	}


	
}


