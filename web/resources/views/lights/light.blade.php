@extends('templates/basic_nav')

@section('javascript')
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <script src="{{ URL::asset('/js/lights.js') }}"></script>
@endsection

@section('content')
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <div ng-app="light" ng-controller="lightControl">
        <div class="col-md-12">Lights</div>
        <div class="col-md-4" ng-repeat="light in lights">
            <span class="row">Name: {$light.name$}</span>
            <span class="row">Reachable: {$light.state.reachable$}</span>
            <span class="row">On: {$light.state.on$}</span>
        </div>
    </div>

@endsection
