<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Client;
use App\Project;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'      => 'Pedro Vicente',
            'email'     => 'pedro.vicente@wtgc.com.br',
            'password'  => bcrypt('123456'),
        ]);

        Client::create([
            'name'          => 'Maria Clara',
            'type'          => 'Fisica',
            'image'         => '',
            'type_document' => 'cpf',
            'document'      => '111-111-111-11',
            'email'         => 'mariaclara@gmail.com',
            'tel_ddd'       => '35',
            'tel_number'    => '911111111',
        ]);

    }
}
