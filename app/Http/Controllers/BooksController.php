<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Books;
use App\Models\Authors;
use App\Models\BooksAuthors;

class BooksController extends Controller
{
    public function saveBook(Request $request){
      $validated = $request->validate([
        'bookTitle' => 'required|min:1|max:255',
        'releaseYear' => 'required|numeric|min:1|max:9999'
      ]);
      $books = new Books;
      $books->title = $validated['bookTitle'];
      $books->release_year = $validated['releaseYear'];
      $books->busy_or_not = isset($request->bookIsFree) ? $request->bookIsFree : 0;
      if($books->save()){
        if(isset($request->authorsId)){
          $insertData = [];
          foreach ($request->authorsId as $authorId) {
            $insertData[] = ['book_id'=>$books->id, 'author_id'=>$authorId];
           }
          if(BooksAuthors::insertOrIgnore($insertData)){
            return redirect()->route('addBook')->with('message','Book have been successfully added');
          }
          return redirect()->route('addBook')->with('message','Book have been successfully added, but error has occurred during authors insertion');
        }
        return redirect()->route('addBook')->with('message','Book have been successfully added');
      }
      return redirect()->route('addBook')->with('message','Something went wrong!');
    }

    public function showBooks($page=0){
      Session::put('booksUrl',request()->fullurl());
      $limit = 5;
      $books = Books::orderBy('books.id', 'desc')->skip($page*$limit)->take($limit)->get();
      $bookIds = $books->pluck('id');
      $authorsData = [];
      if(!empty($bookIds)){
        $authorsData = BooksAuthors::whereIn('books_authors.book_id',$bookIds)->
                                     join('authors','authors.id','=','books_authors.author_id')->
                                     orderBy('authors.id','desc')->
                                     get(
                                         [
                                           'authors.name','authors.surname',
                                           'books_authors.book_id'
                                         ]
                                     );
      }
      return view('showBooks',[
        'authors'=>$authorsData,
        'books'=>$books,
        'page'=>$page,
        'limit'=>$limit
      ]);
    }
    public function setUpSaveBookPage(){
      $authors = Authors::orderBy('id','desc')->get();
      return view('addBook',['authors'=>$authors]);
    }

    public function editBook($bookId){
      $book = Books::find($bookId);
      $bookAuthorIds = BooksAuthors::where('book_id',$bookId)->get(['author_id']);
      if($book){
        return view('addBook',[
          'authors'=>Authors::orderBy('id','desc')->get(),
          'bookData'=>$book,
          'bookAuthorIds'=>$bookAuthorIds
        ]);
      }
      return redirect()->route('showBooks')->with('message', "Book not found with id: $bookId");
    }

    public function saveEditedBook(Request $request, $bookId){
      $validated = $request->validate([
        'bookTitle' => 'required|min:1|max:255',
        'releaseYear' => 'required|numeric|min:1|max:9999'
      ]);

      $book = Books::find($bookId);
      if($book){
        $book->title = $validated['bookTitle'];
        $book->release_year = $validated['releaseYear'];
        $book->busy_or_not = isset($request->bookIsFree) ? $request->bookIsFree : 0;

        $authorsId = isset($request->authorsId) ? $request->authorsId : [];
        BooksAuthors::where('book_id',$book->id)->whereNotIn('author_id',$authorsId)->delete();
        if(!empty($authorsId)){
          $insertData = [];
          foreach ($authorsId as $authorId) {
            $insertData[] = ['book_id'=>$book->id, 'author_id'=>$authorId];
          }
          BooksAuthors::insertOrIgnore($insertData);
        }
        if($book->save()){
          if(session('booksUrl')){
            return redirect(session('booksUrl'))->with('message', 'Book has been successfully updated');
          }
          return redirect()->route('showBooks')->with('message', 'Book has been successfully updated');
        }
        if(session('booksUrl')){
          return redirect(session('booksUrl'))->with('message', 'Something went wrong!');
        }
        return redirect()->route('showBooks')->with('message', 'Something went wrong!');
      }
      if(session('booksUrl')){
        return redirect(session('booksUrl'))->with('message', "Book not found with id: $bookId");
      }
      return redirect()->route('showBooks')->with('message', "Book not found with id: $bookId");
    }

    public function deleteBook($bookId){
      $book = Books::find($bookId);
      if($book){
        if($book->delete()){
          if(session('booksUrl')){
            return redirect(session('booksUrl'))->with('message', 'Book has been successfully deleted');
          }
          return redirect()->route('showBooks')->with('message', 'Book has been successfully deleted');
        }
        if(session('booksUrl')){
          return redirect(session('booksUrl'))->with('message', 'Something went wrong!');
        }
        return redirect()->route('showBooks')->with('message', 'Something went wrong!');
      }
      if(session('booksUrl')){
        return redirect(session('booksUrl'))->with('message', "Book have already been deleted with id: $bookId");
      }
      return redirect()->route('showBooks')->with('message', "Book have already been deleted with id: $bookId");
    }
    //not the best practice, but if it works don't touch it :D
    public function searchBook(Request $request,$page=0){
      Session::put('booksUrl',request()->fullurl());
      $limit = 5;
      $author = $request->query("author");
      $bookTitle = $request->query("bookTitle");
      if(isset($author) && !empty($author) && !isset($bookTitle)){
        $author = explode(" ",$author);
        $authorName = $author[0];
        $books = BooksAuthors::leftJoin('authors','authors.id','=','books_authors.author_id')->
                              leftJoin('books','books.id','=','books_authors.book_id')->
                              orderBy('books.id', 'desc')->skip($page*$limit)->take($limit)->
                              where('name','like',"%$authorName%");
        if(count($author) >= 2){
          $authorSurname = $author[1];
          $books->where('surname','like',"%$authorSurname%");
        }
        $books = $books->get(["books.id","books.title","books.release_year","books.busy_or_not"]);
      }else if(isset($bookTitle) && !empty($bookTitle) && !isset($author)){
        $books = Books::where('title','like',"%$bookTitle%")->
                        orderBy('books.id', 'desc')->skip($page*$limit)->take($limit)->
                        get();
      }else if(isset($bookTitle) && !empty($bookTitle) && isset($author) && !empty($author)){
        $books = BooksAuthors::leftJoin('authors','authors.id','=','books_authors.author_id')->
                      leftJoin('books','books.id','=','books_authors.book_id')->
                      orderBy('books.id', 'desc')->skip($page*$limit)->take($limit)->
                      where('title','like',"%$bookTitle%");
        $author = explode(" ",$author);
        $authorName = $author[0];
        $books = $books->where('name','like',"%$authorName%");
        if(count($author) >= 2){
          $authorSurname = $author[1];
          $books->where('surname','like',"%$authorSurname%");
        }
        $books = $books->get(["books.id","books.title","books.release_year","books.busy_or_not"]);
      }else{
        return redirect()->route("showBooks");
      }
      $bookIds = $books->pluck('id');
      $authorsData = [];
      if(!empty($bookIds)){
        $authorsData = BooksAuthors::whereIn('books_authors.book_id',$bookIds)->
                                     join('authors','authors.id','=','books_authors.author_id')->
                                     orderBy('authors.id','desc')->
                                     get(
                                         [
                                           'authors.name','authors.surname',
                                           'books_authors.book_id'
                                         ]
                                     );
      }
      return view('showBooks',[
        'authors'=>$authorsData,
        'books'=>$books,
        'page'=>$page,
        'limit'=>$limit
      ]);
    }
}
