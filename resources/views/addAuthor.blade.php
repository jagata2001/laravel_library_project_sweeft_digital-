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
        <h1>Add Author</h1>
        @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form class="" action="{{isset($authorData) ? route('editAuthor', $authorData->id) : route('addAuthor') }}" method="post">
          {{ csrf_field() }}
          <input type="text" name="authorName" value="{{$authorData->name ?? ''}}" placeholder="Enter author name" maxlength="255">
          <input type="text" name="authorSurname" value="{{$authorData->surname ?? ''}}" placeholder="Enter author surname" maxlength="255">
          <input type="submit" name="" value="{{isset($authorData) ? 'edit author' : 'add author'}}">
        </form>
        @if (session('message'))
            <div>
                <h2>{{ session('message') }}</h2>
            </div>
        @endif
      </div>
    </body>
</html>
