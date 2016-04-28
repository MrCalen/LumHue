@extends('templates/basic_layout')

@section('title') @endsection

@section('framework')
  <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
        crossorigin="anonymous">

  <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
        integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
        crossorigin="anonymous">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flat-ui/2.2.2/css/flat-ui.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

@endsection

@section('javascript')
    @parent
    <script src="{{ URL::asset('components/angular-bootstrap-colorpicker/js/bootstrap-colorpicker-module.min.js')}}"></script>
    <script src="{{ URL::asset('components/angular-ui-switch/angular-ui-switch.min.js')}}"></script>
    <script src="{{ URL::asset('components/angular-drag-and-drop-lists/angular-drag-and-drop-lists.js')}}"></script>
    <script src="{{ URL::asset('components/angular-local-storage/dist/angular-local-storage.min.js') }}"></script>
@endsection

@section('body')
<nav class="navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ URL::to('/') }}">LumHue</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                @if (Auth::check())
                <li><a href="{{ URL::to('/dashboard') }}">Home</a></li>
                <li><a href="{{ URL::to('/lights') }}">Lights</a></li>
                <li><a href="{{ URL::to('/ambiances') }}">Ambiances</a></li>
                @endif
            </ul>
            <ul class="nav navbar-nav navbar-right">
              @if (session('info'))<li>{{ session('info') }}</li> @endif
              @if (Auth::check())
              @yield('nav_options')
              <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">More <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    @yield('dropdown_options')
                    <li><a href="{{ URL::to('/logout') }}">Log out</a></li>
                  </ul>
                </li>
              @endif
            </ul>
          </div>
        </div>
      </nav>
  @yield('content')

@endsection
