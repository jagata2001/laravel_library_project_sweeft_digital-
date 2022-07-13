<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\BooksController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the 'web' middleware group. Now create something great!
|
*/
Auth::routes();

Route::middleware(["auth"])->group(function () {
  Route::get('/', function () {
      return redirect()->route("showBooks");
  });
  Route::prefix('/books')->group(function () {
    Route::get('/{page?}',
      [BooksController::class, "showBooks"]
    )->name('showBooks')->whereNumber('page');

    Route::get('/{page?}/search',
      [BooksController::class, "searchBook"]
    )->name('searchBook')->whereNumber('page');

    Route::middleware(["isAdmin"])->group(function () {
      Route::get('/create',
        [BooksController::class,'setUpSaveBookPage']
      )->name('addBook');

      Route::post('/create',
        [BooksController::class,'saveBook']
      )->name('addBook');

      Route::get('/{bookId}/edit',
          [BooksController::class,'editBook']
      )->name('editBook')->whereNumber('bookId');

      Route::post('/{bookId}/edit',
          [BooksController::class,'saveEditedBook']
      )->name('editBook')->whereNumber('bookId');

      Route::post('/{bookId}/delete',
        [BooksController::class,'deleteBook']
      )->name('deleteBook')->whereNumber('bookId');
    });
  });

  /* authors section */

  Route::prefix('/authors')->group(function () {

    Route::get('/{page?}',
      [AuthorsController::class,'showAuthors']
    )->name('showAuthors')->whereNumber('page');

    Route::middleware(["isAdmin"])->group(function () {
      Route::prefix('/create')->group(function () {

        Route::get('/', function () {
            return view('addAuthor');
        })->name('addAuthor');

        Route::post('/',
          [AuthorsController::class, 'saveAuthor']
        )->name('addAuthor');

      });

      Route::prefix('/{authorId}/edit')->group(function () {
        Route::get('/',
          [AuthorsController::class, 'editAuthor']
        )->name('editAuthor')->whereNumber('authorId');

        //put/patch
        Route::post('/',
          [AuthorsController::class, 'saveEditedAuthor']
        )->name('editAuthor')->whereNumber('authorId');
      });

      //There should be delete method
      Route::post('/{authorId}/delete',
        [AuthorsController::class, 'deleteAuthor']
      )->name('deleteAuthor')->whereNumber('authorId');
    });
  });
  /* authors section */
});
