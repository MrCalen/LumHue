@extends('templates/basic_nav')

@section('ngApp')ng-app="light"@endsection
@section('ngController')ng-controller="LightController"@endsection

@section('javascript')
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <script src="{{ elixir('js/app.js') }}"></script>
    <script>
      var token = '{{ $token }}';
      var base_url = '{{ URL::to('/') }}';

    </script>
@endsection

@section('content')
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <div>
        <div class="col-md-12">Lights</div>
        <div class="col-md-4" ng-repeat="light in lights">
            <span class="row">Name: {$light.name$}</span>
            <span class="row">Reachable: {$light.state.reachable$}</span>
            <span class="row">On: {$light.state.on$}</span>
        </div>
    </div>

@endsection
