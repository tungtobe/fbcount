<ul class="nav navbar-nav">
  @if (Auth::check())
    <li @if (Request::is('count')) class="active" @endif>
      <a href="/admin/tag">Count</a>
    </li>
    <li @if (Request::is('rank')) class="active" @endif>
      <a href="/admin/upload">Rank</a>
    </li>
  @endif
</ul>

<ul class="nav navbar-nav navbar-right">
  @if (Auth::guest())
    <li><a href="{{ $loginURL}}">Login with FB</a></li>
  @else
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
         aria-expanded="false">{{ Auth::user()->name }}
        <span class="caret"></span>
      </a>
      <ul class="dropdown-menu" role="menu">
        <li><a href="/logout">Logout</a></li>
      </ul>
    </li>
  @endif
</ul>