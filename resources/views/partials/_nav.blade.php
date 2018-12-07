<nav class="bg-white">
  <div class="container pt-3 pb-3">
    <a class="navbar-brand" href="{{ url('/') }}">
        {{ config('app.name', 'Laravel') }}
    </a>


      @guest
        <a class="nav-link" style="display:inline-block;" href="{{ route('login') }}">{{ __('Login') }}</a>
        <a class="nav-link" style="display:inline-block;" href="{{ route('register') }}">{{ __('Register') }}</a>
      @else
        <span class="ml-auto bars-span" style="float:right;" id="openNavSpan"><i class="fas fa-bars fa-lg"></i></span>
        <div class="sidenav" id="mySidenav">
          <a href="javascript:void(0)" class="closebtn"
            id="closeNavBtn">&times;</a>
          <a href="{{url('/')}}" style="margin-top: 20%;"><i class="far fa-chart-bar"></i> Dashboard</a>
          <a href="{{route('orders.index')}}"><i class="fas fa-pen"></i> Requests</a>
          <a href="#"><i class="far fa-user-circle"></i> Your account</a>
          <a href="#">Buy illustrations</a>
          <br><br>
          <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
          </a>
        </div>
        <div class="additional-btns ml-auto d-none d-sm-block">
          <a href="nav-link" href="#"><i class="fas fa-comments"></i> Here to help</a>
          <a id="navbarDropdown" class="dropdown-toggle" href="#" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            <img src="{{asset('icons/icons0.png')}}" alt="user_menu">
            {{ Auth::user()->name }} <span class="caret"></span>
          </a>

          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('logout') }}"
              onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
              {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </div>
      @endguest
  </div>
</nav>
