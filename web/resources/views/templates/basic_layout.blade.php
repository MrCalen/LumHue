<html lang="en" @yield('ngApp')>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Lumhue PLIC application">
  <meta name="author" content="LumHue">

  <title>LumHue @yield('title')</title>
  <link rel="apple-touch-icon" sizes="57x57" href="{{ URL::to('/fav') }}/apple-touch-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="{{ URL::to('/fav') }}/apple-touch-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="{{ URL::to('/fav') }}/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ URL::to('/fav') }}/apple-touch-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="{{ URL::to('/fav') }}/apple-touch-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="{{ URL::to('/fav') }}/apple-touch-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="{{ URL::to('/fav') }}/apple-touch-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="{{ URL::to('/fav') }}/apple-touch-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ URL::to('/fav') }}/apple-touch-icon-180x180.png">
  <link rel="icon" type="image/png" href="{{ URL::to('/fav') }}/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="{{ URL::to('/fav') }}/favicon-194x194.png" sizes="194x194">
  <link rel="icon" type="image/png" href="{{ URL::to('/fav') }}/favicon-96x96.png" sizes="96x96">
  <link rel="icon" type="image/png" href="{{ URL::to('/fav') }}/android-chrome-192x192.png" sizes="192x192">
  <link rel="icon" type="image/png" href="{{ URL::to('/fav') }}/favicon-16x16.png" sizes="16x16">
  <link rel="manifest" href="{{ URL::to('/fav') }}/manifest.json">
  <link rel="mask-icon" href="{{ URL::to('/fav') }}/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="msapplication-TileImage" content="/mstile-144x144.png">
  <meta name="theme-color" content="#ffffff">


  <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
        crossorigin="anonymous">

  <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
        integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
        crossorigin="anonymous">
  <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">

  <link href='https://fonts.googleapis.com/css?family=Sorts+Mill+Goudy:400,400italic' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,300italic,300,100italic,100,500italic,900,700italic,700,500,400italic' rel='stylesheet' type='text/css'>

  @yield('framework')

  @yield('css')
  <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">
  @yield('specific_css')

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body @yield('ngController')>
  @yield('body')

  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
          integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
          crossorigin="anonymous">
  </script>

  <!-- jQuery (necessary for Flat UI's JavaScript plugins) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flat-ui/2.2.2/js/vendor/jquery.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/flat-ui/2.2.2/js/vendor/video.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flat-ui/2.2.2/js/flat-ui.min.js"></script>

  @yield('javascript')

</body>
</html>
