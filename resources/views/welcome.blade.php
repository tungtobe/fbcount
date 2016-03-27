


@extends('layout')

@section('content')
@include('partials.success')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
          <div class="panel-heading">Login</div>
          <div class="panel-body">

            <div class="title"><a href="{{ $loginURL}}">Login</a></div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection