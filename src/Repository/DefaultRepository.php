<?php
declare(strict_types=1);

namespace SONFin\Repository;


/*Design Pattern Repository: classe q trabalha com os Models da app, para o Model nao ter q ser usado diretamente pelo controller
  a class DefaultRepository sera um repos. padrao para todos os models da aplicacao	*/
class DefaultRepository implements RepositoryInterface {

	
	private $modelClass; //atributo para pegar o nome da classe do Model a ser criado
	private $model; //instancia do model criado

	//ao criar obj de RepositoryInterface, sera instanciado um model indicado, para ele ter acesso aos metodos correspondentes do eloquent
	public function __construct(string $modelClass){
		$this->modelClass = $modelClass; //pega o nome do Model a ser criado
		$this->model = new $modelClass; //cria instancia de acordo com nome do Model passado

	}


	public function all(): array
	{
		return $this->model->all()->toArray(); //retornando Collection como array
	}

	public function find(int $id)
	{
		return $this->model->findOrFail($id);
	}

	public function create(array $data)
	{
		$this->model->fill($data);
		$this->model->save();
		return $this->model;

	}

	public function update(int $id, array $data)
	{
		$model = $this->find($id);
		$model->fill($data);  //preenchendo na categoria a ser editada os novos dados enviados
		$model->save();


	}

	public function delete(int $id)
	{
		$model = $this->find($id);
		$model->delete();
	}


}