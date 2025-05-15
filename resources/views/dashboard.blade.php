@extends('layouts.app2')

@section('title', 'SIMJ')

@section('content')
  <h1>Bienvenido{{ auth()->check() ? ', ' . auth()->user()->name : '' }}</h1>
@endsection