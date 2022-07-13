<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Library</title>

        <link rel="stylesheet" href="{{ asset('css/showAuthors.css') }}">
        <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    </head>
    <body>
      @include('partials.header')
      <div class="main">
        <h1>Books Authors</h1>
        @if (count($authors) == 0)
          <h2>There is no any author in database</h2>
        @endif

        @if (session('message'))
            <div>
                <h2>{{ session('message') }}</h2>
            </div>
        @endif
        <ul>
        @foreach ($authors as $author)
          <li>
            <div class="author">
              <h3>{{$author->name}} {{$author->surname}}</h3>
              @if(Auth::user()->role == 1)
              <input type="button" onclick="window.location = '{{ route('editAuthor',$author->id) }}'" value="edit author" style="margin-left:10px;">

              <form class="" action="{{ route('deleteAuthor',$author->id) }}" method="post">
                {{ csrf_field() }}
                <input type="submit" name="" value="delete author" style="margin-left:10px;">
              </form>
              @endif
          </div>
          </li>
        @endforeach
        </ul>
        <div class="next-previous">
          @if ($page != 0)
            <button type="button" onclick="window.location.href='{{ route('showAuthors', $page-1) }}'">previous page</button>
          @endif
          @if (count($authors) == $limit)
            <button type="button" onclick="window.location.href='{{ route('showAuthors', $page+1) }}'">next page</button>
          @endif
        </div>
      </div>
    </body>
</html>
