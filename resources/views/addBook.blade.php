<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Library</title>

        <link rel="stylesheet" href="{{ asset('css/addBook.css') }}">
        <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    </head>
    <body>
      @include('partials.header')
      <div class="main">
        <h1>Add Book</h1>
        @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form class="" action="{{isset($bookData) ? route('editBook', $bookData->id) : route('addBook') }}" method="post">
          {{ csrf_field() }}
          <div class="">
            <input type="text" name="bookTitle" value="{{$bookData->title ?? ''}}" placeholder="Enter book title" maxlength="255">
            <label for="releaseYear">Enter Release Year</label>
            <input type="number" name="releaseYear" min="0" max="2099" step="1" placeholder="2001" value="{{$bookData->release_year ?? ''}}" id="releaseYear">
            <label for="busyOrNot">Check if book is busy</label>
            <input type="checkbox" name="bookIsFree" value="1" id="busyOrNot"
              {{
                isset($bookData) ? $bookData->busy_or_not == 1 ? 'checked' : '' : ''
              }}
            >
          </div>
          <div class="authors">
            <div class="search-box">
              <input type="text" value="" id="searchText" placeholder="Enter author">
              <button type="button" id="searchButton">Search</button>
            </div>

            <ul>
              @foreach ($authors as $author)
                  <li>
                    <div class="author-data">
                      <input type="checkbox" name="authorsId[]" value="{{ $author->id }}" {{
                                                                                        isset($bookAuthorIds)
                                                                                         ?
                                                                                            $bookAuthorIds->contains('author_id',$author->id)
                                                                                              ?
                                                                                                'checked'
                                                                                              :
                                                                                                 ''
                                                                                         :
                                                                                           ''
                                                                                          }}>
                      <p>{{ $author->name }} {{ $author->surname }}</p>
                    </div>
                  </li>
              @endforeach
            </ul>
          </div>
          <input type="submit" name="" value="{{isset($bookData) ? 'edit book' : 'add book'}}">
        </form>
        @if (session('message'))
            <div>
                <h2>{{ session('message') }}</h2>
            </div>
        @endif
    </div>
    <script src="{{ asset('js/addBook.js') }}"></script>
    </body>
</html>
