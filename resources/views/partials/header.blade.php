<div class="header">
  <div class="link">
    <a href="{{route('showBooks')}}" class="main-text">Library</a>
    <a href="{{route('showAuthors')}}">Authors</a>
    @if(Auth::user()->role == 1)
      <a href="{{route('addBook')}}">Add Book</a>
      <a href="{{route('addAuthor')}}">Add Author</a>
    @endif
  </div>

  <form class="search" action="{{route('searchBook',0)}}" method="get">
    <input type="text" name="bookTitle" value="{{request()->query('bookTitle') ?? ''}}" placeholder="Enter book title">
    <input type="text" name="author" value="{{request()->query('author') ?? ''}}" placeholder="Enter Author">
    {{csrf_field()}}
    <input type="submit" class="search-submit" value="Search">
  </form>
  <p class="username">{{ Auth::user()->name }}</p>
  <a class="logout" href="{{ route('logout') }}"
     onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();">
      {{ __('Logout') }}
  </a>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
      @csrf
  </form>
</div>
