<?php
declare(strict_types=1);

namespace SONFin\Models;

use Illuminate\Database\Eloquent\Model;

/**
* class model referente a tabela category_costs
*/
class CategoryCost extends Model //class Eloquent para Models
{
	/*quando estende de Model, a class automaticamente ganha acesso aos metodos do Eloquent de manipulacao de dados, como
	save(), find(), all(), etc, sem precisar q eles sejem declarados; 
	esses metodos serao usados numa determinada tabela do banco de dados, tabela essa q precisa
	ser uma convecao do nome da classe, mas em letras minusculas, com anderline separando as palavras e terminando em plural
	Ex: os metodos da classe CategoryCost terao efeito numa tabela q se chame category_costs; Outros exemplos:
	User = users / Bill = bills / CarMotor = car_motors */
	
	//campos q podem ser persistidos no banco; se for passado array com campos diferentes desses, eles nao seram incluidos
	protected $fillable = [
		'name'
	];
}