@php
    use Carbon\Carbon;
    $title = __('Dashboard');
@endphp
@extends('layouts.app')
@section('title', $title)
@section('content')
    <h1 class="text-4xl text-center">
        Welcome {{ auth()->user()->name }}
    </h1>
@endsection
