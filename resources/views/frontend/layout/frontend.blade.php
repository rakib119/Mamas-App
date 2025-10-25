<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Baby Kick Counter</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{asset('assets/css/style.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/css/all.min.css')}}" />
  @yield('css')
</head>
<body>

    @yield('content')
    <section id="android-menu">
        <div class="menu-wrapper">
            <div class="android-menu-bar">
                <a class="android-menu-item nav-link active" href="{{ route('home')}}">
                    <div class="item-inner active">
                        <i class="fa-solid fa-home"></i>
                        <p>Home</p>
                    </div>
                </a>
                <a class="android-menu-item nav-link " href="{{ route('routine.index')}}">
                    <div class="item-inner">
                        <i class="fa-solid fa-utensils"></i>
                        <p>Routine</p>
                    </div>
                </a>
                <a class="android-menu-item nav-link" href="{{ route('kick.history')}}">
                    <div class="item-inner">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <p>History</p>
                    </div>
                </a>
                <a class="android-menu-item nav-link " href="{{ route('profile')}}">
                    <div class="item-inner">
                        <i class="fa-solid fa-user"></i>
                        <p>Profile</p>
                    </div>
                </a>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{asset('assets/js/all.min.js')}}"></script>
    <script src="{{asset('assets/js/script.js')}}"></script>
    @yield('javaScript')

</body>
</html>
