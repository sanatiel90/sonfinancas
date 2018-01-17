<?php
declare(strict_types=1);

namespace SONFin\View;

use Psr\Http\Message\ResponseInterface; 
use Zend\Diactoros\Response;



class ViewRenderer implements ViewRendererInterface
{
	
	//no construtor, cria uma instancia do ambiente twig
	function __construct(\Twig_Environment $twigEnvironment)
	{
		$this->twigEnvironment = $twigEnvironment;
	}

	//metodo da interface
	public function render(string $template, array $context = []): ResponseInterface
	{
		$result = $this->twigEnvironment->render($template, $context);
		$response = new Response();
		$response->getBody()->write($result);	//escrevendo o resultado, q é o template desejado, no body da resposta
		return $response;  
	}


}