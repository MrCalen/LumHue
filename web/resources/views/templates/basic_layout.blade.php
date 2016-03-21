<html lang="en" @yield('ngApp')>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Lumhue PLIC application">
  <meta name="author" content="LumHue">

  <title>@section('title') LumHue @endsection</title>

  <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
        crossorigin="anonymous">

  <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
        integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
        crossorigin="anonymous">
  <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">

  @yield('framework')

  @yield('css')
  <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body @yield('ngController')>
  @yield('body')

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
          integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
          crossorigin="anonymous">
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular.min.js"></script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <!-- jQuery (necessary for Flat UI's JavaScript plugins) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flat-ui/2.2.2/js/vendor/jquery.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/flat-ui/2.2.2/js/vendor/video.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flat-ui/2.2.2/js/flat-ui.min.js"></script>

  @yield('javascript')

</body>
</html>
