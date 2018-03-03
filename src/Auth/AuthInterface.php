<?php
declare(strict_types=1);

namespace SONFin\Auth;

//define os metodos a serem usados pela classe de autenticacao
interface AuthInterface
{
	public function login(array $credentials): bool; //met. faz o login no sistema 
	public function check(): bool; //met. q verifica se user esta logado quando acessa as rotas
	public function logout();  //faz logout


}