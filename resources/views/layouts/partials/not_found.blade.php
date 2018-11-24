@extends('layouts.app')

@section('title', 'Page Not Found')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/not_found.css') }}">
@endsection

@section('content')
<div id="notfound">
  <div class="notfound">
    <div class="notfound-404">
      <h1>4<span>0</span>4</h1>
    </div>
    <h2>the page you requested could not found</h2>
    <a href="{{ route('home') }}" class="btn btn-success">Go Home</a>
  </div>
</div>
@endsection
