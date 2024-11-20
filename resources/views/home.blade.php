@extends('layouts.template')
{{-- extends : untuk import file blade --}}

@section('content')
{{-- section : untuk mengisi @yield pada file yang di import --}}
<div class="jumbotron py-4 px-5">
  @if (Session::get('failed'))
    <div class="alert alert-danger">{{ Session::get('failed') }}</div>
  @endif
  <h1 class="display-4">
    {{-- @if(Auth::check()) --}}
    Selamat Datang {{ Auth::user()->name }}!
    {{-- @else
    Selamat Datang
    @endif --}}
  </h1>
  <hr class="my-4">
    <p>Aplikasi ini digunakan hanya oleh pegawai administrator APOTEK. Digunakan untuk mengelola data obat, penyetokan, juga pembelian (kasir).</p>
  </div>
@endsection