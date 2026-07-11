@extends('layouts.admin') @section('title','Panel administrativo') @section('content')<h2>Panel administrativo</h2><p>Bienvenido, {{auth()->user()->name}}.</p>@endsection
