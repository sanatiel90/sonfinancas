<?php

use Phinx\Seed\AbstractSeed;

class UsersSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        //usando Faker para gerar dados aleatorios (como param a linguagem dos dados q serao gerados)
        $faker = \Faker\Factory::create('pt_BR');

        //instancia da tabela
        $user = $this->table('users');

        //array q vai conter os dados aleatorios
        $data = [];

        //usuario padrao
         $user->insert([
          'first_name' => 'Admin',
          'last_name' => $faker->lastName(),
          'email' => 'admin@user.com',
          'password' => '123456',
          'created_at' => date('Y-m-d H:i:s'),
          'updated_at' => date('Y-m-d H:i:s'),

         ])->save();

        //foreach para popular o array com dados do faker (a func range() indica q o foreach vai ser executado 5 vezes)
        foreach (range(1, 4) as $value) {
           $data[] = [
              'first_name' => $faker->firstName(), //propriedade da class Faker q gera um nome aleatorio
              'last_name' => $faker->lastName(),
              'email' => $faker->unique()->email(),
              'password' => '123456',
              'created_at' => date('Y-m-d H:i:s'),
              'updated_at' => date('Y-m-d H:i:s'),
           ];
        }

        //inserindo dados na tabela (dados do array preenchido com faker)
        $user->insert($data)->save();


       /* modo de popular sem usar o Faker
        $CategoryCosts->insert([
            [   
              'name' => 'Category 1',
              'created_at' => date('Y-m-d H:i:s'),
              'updated_at' => date('Y-m-d H:i:s'), 

            ],

            [
              'name' => 'Category 2',
              'created_at' => date('Y-m-d H:i:s'),
              'updated_at' => date('Y-m-d H:i:s'), 

            ],

            [
              'name' => 'Category 3',
              'created_at' => date('Y-m-d H:i:s'),
              'updated_at' => date('Y-m-d H:i:s'), 

            ],


        ])->save();*/

    }
}
