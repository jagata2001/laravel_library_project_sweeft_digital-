<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Books;

class CreateBooks extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Books::insert([
        [
         'title'=>'vefxis tyaosani',
         'release_year'=>'1080',
         'busy_or_not'=>1,

       ],
       [
         'title'=>'The Gambler',
         'release_year'=>'1866',
         'busy_or_not'=>0,
       ]
      ]);
    }
}
