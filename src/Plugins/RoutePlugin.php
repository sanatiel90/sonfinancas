<?php
declare(strict_types = 1);

namespace SONFin\Plugins;

use Aura\Router\RouterContainer; //importando class RouterContainer da lib AuraRouter q foi add via composer
use Interop\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface; 
use SONFin\ServiceContainerInterface;
use Zend\Diactoros\ServerRequestFactory; //lib zendframekwork zend-diactoros


/**
* class plugin q gerencia as rotas da aplicacao
*/
class RoutePlugin implements PluginInterface
{
	//metodo para registrar/adicionar novos servicos (no caso da class RoutePlugin, serao servidos de rotas)
	public function register(ServiceContainerInterface $container){

		//instancia de RouterContainer (classe da lib AuraRouter que cria serviços de rotas)
		$routerContainer = new RouterContainer();

		/*criando serviços para rotas*/
		//map: servico resp. por registrar as rotas da app
		$map = $routerContainer->getMap();

		//matcher: servico resp. por identificar a rota q esta sendo usada, precisa receber a requisicao (instancia de RequestInterface) q foi feita
		$matcher = $routerContainer->getMatcher();

		//generator: servico resp. por criar links(redirecionamentos) com base nas rotas criadas
		$generator = $routerContainer->getGenerator();

		//request: servico das requisicoes, obtivo atraves do metodo protected getRequest(); 
		$request = $this->getRequest(); 


		//add os servicos criados dentro do container de servicos, para q possam ser usados em outros lugares da app 
		$container->add('routing', $map);
		$container->add('routing.matcher',$matcher);
		$container->add('routing.generator',$generator);
		$container->add(RequestInterface::class, $request); //servico tera como nome o nome da class RequestInterface


		/*add servico com metodo com retardo; add request ao matcher; ContainerInterface: instancia do pimple que permite acessar todos os servicos ja 	      registrados*/
		$container->addLazy('route', function(ContainerInterface $container){
			$matcher = $container->get('routing.matcher');	//recuperando matcher atraves do servico 'routing.matcher'
			$request = $container->get(RequestInterface::class); //recuperando request atraves do servico 'RequestInterface::class'

			return $matcher->match($request); 
			//atribuindo request(requsicao q foi feita) ao matcher atraves do met. match() e retornando a rota a ser usada pelo sistema 
		});

		/* método lazy: um servico adicionado por meio dele não é criado automaticamente (como acontece com o add()); o serv. só vai ser criado
		   quando o container for de fato chamado; no caso do add(), um servico pode ser criado apenas informando qual será o nome e tbm
		   fornecendo uma instancia para o servico (como aconteceu com map, matcher, generator), porem existem servicos q nao sao tao simples e precisam de uma logica para serem criados, as vezes precisando de instancias de outros servicos para criar seu proprio servico; para esses servicos mais complexos, é preciso usar o addLazy() pois ele fornece um retardo de execucao necessario para criar a sua logica; no caso do servico 'route' criado anteriormente, foi necessario usar outros 2 servicos (matcher e request) para poder retornar seu proprio servico (q é  a rota a ser usada pelo sistema, fruto da interacao entre matcher e request)	 */
 
	}


	/*metodo para pegar a requisicao, retorna uma instancia de RequestInterface (é uma interface padrao da PSR 7 para requisicoes)
	  será usada no serviço de Matcher		*/
	protected function getRequest(): RequestInterface
	{	
		/*fromGlobals(): metodo static da class ServerRequestFactory presente na lib zendframekwork zend-diactoros; retorna um obj RequestInterface contendo os arrays super globais do php referent a requisicoes  */
		return ServerRequestFactory::fromGlobals($_SERVER,$_GET,$_POST,$_COOKIE,$_FILES);
	}


}



/* TXT AULA

Conforme avançamos em nosso projeto, falamos de novos padrões a serem seguidos. Neste módulo, falaremos de mais uma PSR.

Falaremos da PSR-7, que fala sobre HTTP Message Interface.

Quando estamos trabalhando em uma aplicação web, usando PHP, acabamos trabalhando com o objeto Request, que é enviado ao servidor. Antigamente, antes desta PSR, os frameworks trabalhavam cada um de uma forma.

Vale lembrar que, não é legal acessar este objeto $Request, diretamente. Esta PSR veio para manter a interoperabilidade entre os frameworks e bibliotecas. Desta forma, temos uma padrão para trabalhar com o objeto Request.

Um dos objetivos deste conteúdo é mostrar que, em um futuro próximo, vocês não ficarão dependentes de algum framework ou, muito menos, dependente de uma biblioteca utilizada por algum framework. Com as PSRs, vocês poderão trocar de bibliotecas quando quiserem, uma vez que, todas, trabalham com o mesmo padrão.

A biblioteca Aura.Router implementa a PSR-7. Se vocês encontrassem alguma outra biblioteca que, também, a implementasse, vocês poderiam trocar a biblioteca, tranquilamente.

Voltando ao arquivo RoutePlugin, faremos algumas considerações, para justificar o motivo de falar sobre a PSR-7.

Para o Aura.Route executar a sua função de reconhecer as rotas acessadas, ele precisa reconhecer as requisições enviadas para o servidor. Enviaremos estes dados da requisição para o Aura, utilizando a PSR-7. Por este motivo, falamos sobre este padrão, anteriormente.

Para isso, utilizaremos uma biblioteca da Zend, que se chama zend-diactoros. Diactoros, significa mensageiro, em grego. Esta biblioteca trabalha com a PSR-7. Com ela conseguimos enviar mensagem e resposta, neste padrão.

Para instalar esta biblioteca, rodem o comando abaixo, no terminal:

composer require zendframework/zend-diactoros:1.3.10

Fiquem atendos às versões que estamos informando nas instalações. Depois de instalar o diactoros, voltaremos ao arquivo RoutePlugin.

Primeiro, não esqueçam de adicionar o strict_types, antes do namespace, para que possamos trabalhar com definição de retorno para os métodos.

declare(strict_types=1);
namespace SONFin\Plugins;
Criaremos um método request, para que possamos passar os dados da request para o método getMatcher, do Aura.

protected function getRequest(): RequestInterface
{
    return ServerRequestFactory::fromGlobals(
        $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
    );
}
Vejam que estamos retornando a classe ServerRequestFactory, do diactoros, em que acessamos, de forma estática, um método fromGlobals. Este método retornará um objeto com todas as variáveis globais do PHP, referentes às requisições. Teremos que informar todas as globais que farão parte deste objeto.

Significa que nunca mais acessaremos uma variável global, diretamente, isso não se faz há muito tempo.

O método getRequest retornará uma RequestInterface, que passaremos para o método getMatcher. Criaremos o primeiro método do tipo "retardado" ou Lazy.

Quando adicionamos um serviço, já estamos passando uma instância formada para o nosso container de serviço.

$container->add('routing', $map);

Até acabar a vida útil da nossa aplicação, teremos, apenas, uma instância gravada em nosso container de serviços. Toda vez que utilizarmos este serviço, estaremos utilizando, sempre, a mesma instância, porque faz parte do mesmo objeto. Isso quer dizer que não criaremos uma nova instância, a cada vez que acessarmos o serviço.

Pode existir algum caso que precisemos elaborar melhor o serviço e por isso, desejamos que ele seja formado, posteriormente, em relação aos demais serviços, o que chamamos de retardo.

Para criarmos a relação da rota que foi acessada, entramos, exatamente, neste caso. O usuário acessará a rota depois de todos os serviços já estarem carregados e não seria possível o serviço prever. Desta forma, precisamos que este serviço seja criado, posteriormente.

Como estamos formando o serviço com retardo, já podemos acessar os serviços que já foram criados. Utilizaremos o serviço matcher.

Vejam como ficou o arquivo RoutePlugin.php

<?php
declare(strict_types=1);

namespace SONFin\Plugins;


use Aura\Router\RouterContainer;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use SONFin\ServiceContainerInterface;
use Zend\Diactoros\ServerRequestFactory;

class RoutePlugin implements PluginInterface
{

    public function register(ServiceContainerInterface $container)
    {
        $routerContainer = new RouterContainer();
        // Registrar as rotas da aplicação 
        $map = $routerContainer->getMap();
        // Tem a função de identificar a rota que está sendo acessada 
        $matcher = $routerContainer->getMatcher();
        // Tem a funão de gerar links com base nas rotas registradas
        $generator = $routerContainer->getGenerator();
        $request = $this->getRequest();

        $container->add('routing', $map);
        $container->add('routing.matcher', $matcher);
        $container->add('routing.generator', $generator);
        $container->add(RequestInterface::class, $request);

        $container->addLazy('route', function (ContainerInterface $container) {
            $matcher = $container->get('routing.matcher');
            $request = $container->get(RequestInterface::class);
            return $matcher->match($request);
        });

    }

    protected function getRequest(): RequestInterface
    {
        return ServerRequestFactory::fromGlobals(
            $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
        );
    }
}
?>
Vejam que, primeiro estamos chamando o método getRequest.

$request = $this->getRequest();

Depois, estamos adicionando o serviço com o mesmo nome da classe.

$container->add(RequestInterface::class, $request);

E, por último, estamos adicionando o serviço, com retardo.

$container->addLazy('route', function (ContainerInterface $container) {
    $matcher = $container->get('routing.matcher');
    $request = $container->get(RequestInterface::class);
    return $matcher->match($request);
});
Vejam que passamos um identificador route e depois passamos uma função que terá a lógica do serviço. A função recebe um ContainerInterface, que é a vantagem de trabalhar com container de serviços. Receberemos uma instância do Pimple, com todos os serviços registrados. Desta forma, podemos utilizar os serviços, dentro da função.

Foi o que fizemos, pegamos dois serviços, já registrados, no container.

$matcher = $container->get('routing.matcher');
$request = $container->get(RequestInterface::class);
Em seguida, retornamos a nossa requisição, prontinha, para ser utilizada e interpretada, pelo sistema.

return $matcher->match($request);

Conclusão
Vocês puderam ver que é um pouco trabalhoso no início, mas depois que estiver tudo configurado, vocês poderão ver a facilidade de criar uma rota, no sistema.

Leiam, novamente, se for necessário, tentem entender a lógica de toda configuração, para que vocês possam fixar o conceito.
*/