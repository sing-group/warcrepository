<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use App\User;
use Webpatser\Uuid\Uuid;

class UsersSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     *
     */
    public function run()
    {
        DB::table('users')->delete();

        User::create([
            'uuid' => Uuid::generate(4)->string,
            'name' => 'Admin',
            'first_name' => 'Administrator' ,
            'last_name' => 'WARC-Repository' ,
            'institution' => 'Escuela Superior de Ingeniería Informática de Ourense' ,
            'email' => 'infowarcrepository@gmail.com',
            'password' => bcrypt('admin'),
            'verified' => 1,
            'role' => 'admin',
        ]);

        User::create([
            'uuid' => Uuid::generate(4)->string,
            'name' => 'Jane',
            'first_name' => 'Jane' ,
            'last_name' => 'Hanssen' ,
            'institution' => 'Escuela Superior de Ingeniería Informática de Ourense' ,
            'email' => 'jane@warcrepository.com',
            'password' => bcrypt('secret'),
            'verified' => 1,
            'role' => 'demo',
        ]);

        User::create([
            'uuid' => Uuid::generate(4)->string,
            'name' => 'John',
            'first_name' => 'John' ,
            'last_name' => 'Hanssen' ,
            'institution' => 'Escuela Superior de Ingeniería Informática de Ourense' ,
            'email' => 'john@warcrepository.com',
            'password' => bcrypt('secret'),
            'verified' => 1,
            'role' => 'demo',
        ]);
    }
}
