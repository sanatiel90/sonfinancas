<?php
//arq do phinx referen as migrations do bd

include __DIR__ . '/vendor/autoload.php';

//vai receber array de configuracao
$db = include __DIR__ . '/config/db.php';

/*func do php 7: vai pegar as info q tem no array $db['development'] e colocar cada informacao (driver,host,database,etc)
numa var (adapter,host,name), q vai ser usada pelo phinx */
list(
	'driver' => $adapter,
	'host' => $host,
	'database' => $name,
	'username' => $user,
	'password' => $pass,
	'charset' => $charset,
	'collation' => $collation

) = $db['development'];

//phinx retorna config das migrations
return [
	//caminhos onde estarao os arq de migrations e seeds
	'paths' => [
		'migrations' => [
			__DIR__ . '/db/migrations'
		],
		'seeds' => [
			__DIR__ . '/db/seeds'
		]

	],

	'environments' => [
		//informa qual tabela ira armazenar as migrations que já foram executadas no bd, evitando q sejam executadas novamente
		'default_migration_table' => 'migrations',
		//config de conex com o bd {sao as infor de user,password,db, etc q vao ser usadas pelo phinx}
		'default_database' => 'development',
		/*dados da conexao fornecidos ao phinx foram pegues no array $db['development'] atraves da func list(); esses dados de conex são apenas para o ambiente development (que esta setado como padrao)*/
		'development' => [
			'adapter' => $adapter,
			'host' => $host,
			'name' => $name,
			'user' => $user,
			'pass' => $pass,
			'charset' => $charset,
			'collation' => $collation

		]



	],


];