<?php
declare(strict_types=1);

namespace SONFin\Repository;


/*Design Pattern Factory: classe q cria diversos repositorios*/
class RepositoryFactory  {

	public static function factory(string $modelClass)
	{
		return new DefaultRepository($modelClass);
	}
	

}