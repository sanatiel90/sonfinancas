<?php
declare(strict_types=1);

namespace SONFin\Repository;


//interface q define os metodos q devem ser criados pelos repositorios
interface RepositoryInterface {

	public function all(): array;

    public function find(int $id);

	public function create(array $data);

	public function update(int $id, array $data);

	public function delete(int $id);


}