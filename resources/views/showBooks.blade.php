<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Library</title>

        <link rel="stylesheet" href="{{ asset('css/showBooks.css') }}">
        <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    </head>
    <body>
      @include('partials.header')
      <div class="main">
        <h1>Books</h1>
        @if (count($books) == 0)
          <h2 class="message">There is no any book in database</h2>
        @endif

        @if (session('message'))
            <div>
                <h2 class="message">{{ session('message') }}</h2>
            </div>
        @endif
        <ul>
        @foreach ($books as $book)
          <li>
            <div class="container">
              <div class="book">
                <p class="book-title">{{$book->title}}</p>
                <div class="additional-data">
                  <p>Release year: {{$book->release_year}}</p>
                  <p>Book is: {{$book->busy_or_not == 1 ? 'Busy' : 'Free'}}</p>
                </div>
                <div class="authors-data">
                  <p>Authors</p>
                  <div class="authors">
                    @foreach ($authors->where('book_id',$book->id) as $author)
                      <p>{{$author->name}} {{$author->surname}},</p>
                    @endforeach
                  </div>
                </div>
              </div>
              @if(Auth::user()->role == 1)
              <div class="actions">
                <button type="button" onclick="window.location.href='{{ route('editBook',$book->id) }}'">Edit book</button>
                <form class="" action="{{ route('deleteBook',$book->id) }}" method="post">
                  {{ csrf_field() }}
                  <input type="submit" name="" value="Delete book">
                </form>
              </div>
              @endif
            </div>
          </li>
        @endforeach
        </ul>
        <div class="next-previous">
          @if ($page != 0)
            @if (request()->query('author') === null && request()->query('bookTitle') === null)
              <button type="button" onclick="window.location.href='{{ route('showBooks', $page-1) }}'">previous page</button>
            @else
              <button type="button" onclick="window.location.href='{{ route('searchBook', ['page'=>$page-1,'bookTitle'=>request()->query('bookTitle'),'author'=>request()->query('author'),'_token'=>request()->query('_token')]) }}'">previous pageee</button>
            @endif
          @endif
          @if (count($books) == $limit)
            @if (request()->query('author') === null && request()->query('bookTitle') === null)
              <button type="button" onclick="window.location.href='{{ route('showBooks', $page+1) }}'">next page</button>
            @else
              <button type="button" onclick="window.location.href='{{ route('searchBook', ['page'=>$page+1,'bookTitle'=>request()->query('bookTitle'),'author'=>request()->query('author'),'_token'=>request()->query('_token')]) }}'">next page</button>
            @endif
          @endif
        </div>
      </div>
    </body>
</html>
