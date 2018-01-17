<?php
declare(strict_types=1);

namespace SONFin\View;

use Psr\Http\Message\ResponseInterface; 

//interface q define metodo para renderizar
interface ViewRendererInterface{

	//metodo para renderizar; param: nome do template e array de contexto(array contendo valores q serão acessados como variáveis na view, atraves da notação {{}} do twig; caso esse contexto nao seja informado ele vira vazio por padrao); retorna um ResponseInterface
	public function render(string $template, array $context = []): ResponseInterface;

}