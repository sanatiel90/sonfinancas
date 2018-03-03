<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
   public function up(){
    
    //cria tabela category_costs com os respectivos campos (save para criar a tab)
    $this->table('users')
        ->addColumn('first_name', 'string')
        ->addColumn('last_name', 'string')
        ->addColumn('email', 'string')
        ->addColumn('password', 'string')
        ->addColumn('created_at', 'datetime')
        ->addColumn('updated_at', 'datetime')
        ->addIndex(['email'],['unique' => true]) //add um index para indicar que campo email sera unico
        ->create();

  
   }

   //metodo para desfazer modificacoes no bd
   public function down(){
        //deleta tab
        $this->dropTable('users');
    
   }
}
