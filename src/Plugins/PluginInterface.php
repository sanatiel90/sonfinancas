<?php
declare(strict_types = 1);

namespace SONFin\Plugins;

//todos os plugins usarao essa interfac
interface PluginInterface {
	
	//metodo q sera usa por todos os Plugins; registra um novo plugin com base numa instancia de servico de ServiceContainerInterface
	public function register(ServiceContainerInterface $serviceContainer);


}

/*
Plugins
classes q vao implementar novas funcionalidades, recursos, libs, servicos à classe Application, sem q para isso tenha q mexer no cod base dela, para evitar 
bugs e quebras de logica
*/