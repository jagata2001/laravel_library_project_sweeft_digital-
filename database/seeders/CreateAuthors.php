<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Authors;

class CreateAuthors extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Authors::insert([
        [
         'name'=>'Shota',
         'surname'=>'Rustaveli'
       ],
       [
         'name'=>'Fyodor',
         'surname'=>'Dostoevsky'
       ]
      ]);
    }
}
