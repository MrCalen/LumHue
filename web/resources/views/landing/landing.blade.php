@extends('templates/basic_layout')

@section('title') - Home @endsection

@section('specific_css')
  <link rel="stylesheet" href="{{ asset('css/landing/landing.css') }}"/>
@endsection

@section ('body')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <a href="{{ URL::to('/login') }}" class="btn btn-1 btn-1e pull-right" id="login">@if (Auth::check())Home @else Login @endif</a>
      </div>
    </div>
    <div class="row">
      <div class="title col-md-8 col-md-offset-2 center text-center">
        <h1 class="hidden-xs"><b>Lum Hue</b></h1>
        <h1 class="visible-xs"><b>Lum Hue</b></h1><hr class="separator"/>
        <h5>Bring lights to your home</h5>
        <a class="btn btn-1 btn-1e">Discover Now</a>

      </div>

    </div>
  </div>
@endsection
