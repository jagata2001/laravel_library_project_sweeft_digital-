<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Authors;

class AuthorsController extends Controller
{
    public function saveAuthor(Request $request){
      $validated = $request->validate([
        'authorName' => 'required|min:1|max:255',
        'authorSurname' => 'required|min:1|max:255'
      ]);

      $authors = new Authors;
      $authors->name = $validated['authorName'];
      $authors->surname = $validated['authorSurname'];
      if($authors->save()){
        return redirect()->route('addAuthor')->with('message', 'Author successfully added');
      }
      return redirect()->route('addAuthor')->with('message', 'Something went wrong!');
    }

    public function showAuthors($page=0){
      Session::put('authorsUrl',request()->fullurl());
      $limit = 5;
      $authorsData = Authors::orderBy('id', 'desc')->skip($page*$limit)->take($limit)->get();
      return view('showAuthors',[
        'authors'=>$authorsData,
        'page'=>$page,
        'limit'=>$limit
      ]);
    }

    public function editAuthor($authorId){
      $author = Authors::find($authorId);
      if($author){
        return view('addAuthor',['authorData'=>$author]);
      }
      return redirect()->route('showAuthors')->with('message', "Author not found with id: $authorId");
    }

    public function saveEditedAuthor(Request $request,$authorId){
      $validated = $request->validate([
        'authorName' => 'required|min:1|max:255',
        'authorSurname' => 'required|min:1|max:255'
      ]);

      $author = Authors::find($authorId);
      if($author){
        $author->name = $validated['authorName'];
        $author->surname = $validated['authorSurname'];
        if($author->save()){
          if(session('authorsUrl')){
            return redirect(session('authorsUrl'))->with('message', 'Author has been successfully updated');
          }
          return redirect()->route('showAuthors')->with('message', 'Author has been successfully updated');
        }
        if(session('authorsUrl')){
          return redirect(session('authorsUrl'))->with('message', 'Something went wrong!');
        }
        return redirect()->route('showAuthors')->with('message', 'Something went wrong!');
      }
      if(session('authorsUrl')){
        return redirect(session('authorsUrl'))->with('message', "Author not found with id: $authorId");
      }
      return redirect()->route('showAuthors')->with('message', "Author not found with id: $authorId");
    }

    public function deleteAuthor($authorId){
      $author = Authors::find($authorId);
      if($author){
        if($author->delete()){
          if(session('authorsUrl')){
            return redirect(session('authorsUrl'))->with('message', 'Author has been successfully deleted');
          }
          return redirect()->route('showAuthors')->with('message', 'Author has been successfully deleted');
        }
        if(session('authorsUrl')){
          return redirect(session('authorsUrl'))->with('message', 'Something went wrong!');
        }
        return redirect()->route('showAuthors')->with('message', 'Something went wrong!');
      }
      if(session('authorsUrl')){
        return redirect(session('authorsUrl'))->with('message', "Author have already been deleted with id: $authorId");
      }
      return redirect()->route('showAuthors')->with('message', "Author have already been deleted with id: $authorId");
    }
}
