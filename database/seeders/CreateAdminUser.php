<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class CreateAdminUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

       User::insert([
         [
          'name'=>'admin',
          'email'=>'admin@admin.com',
          'role'=>1,  //role=1 to access admin functionality
          'password'=> bcrypt('admin')
         ],
         [
          'name'=>'user',
          'email'=>'user@user.com',
          'role'=>0,
          'password'=> bcrypt('user')
         ]
       ]);
    }
}
