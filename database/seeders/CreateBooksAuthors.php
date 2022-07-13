<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Books;
use App\Models\Authors;
use App\Models\BooksAuthors;

class CreateBooksAuthors extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bookIds = Books::whereIn('title',
          [
            'vefxis tyaosani',
            'The Gambler'
          ]
        )->orderBy('id','ASC')->take(2)->get('id')->pluck("id");
        if(!empty($bookIds) && count($bookIds) == 2){
          $authorIds = Authors::whereIn('name',
            [
              'Shota',
              'Fyodor'
            ]
          )->whereIn('surname',
            [
              'Rustaveli',
              'Dostoevsky'
            ]
          )->orderBy('id','ASC')->take(2)->get('id')->pluck("id");
          if(!empty($authorIds) && count($authorIds) == 2){
            BooksAuthors::insert([
              ["book_id"=>$bookIds[0],"author_id"=>$authorIds[0]],
              ["book_id"=>$bookIds[1],"author_id"=>$authorIds[1]]
            ]);
          }
        }
    }
}
