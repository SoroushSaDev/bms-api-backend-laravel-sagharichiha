@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
@section('title', $device->name)
@section('content')
    <div class="text-center">
        <h2 class="text-gray-900 dark:text-gray-100 text-3xl">
            {{ $device->name }}
        </h2>
    </div>
    <hr class="my-5">
    <ul class="space-y-5">
        <li>
            {{ 'Type : ' . ($device->type ?? '---') }}
        </li>
        <li>
            {{ 'Brand : ' . ($device->brand ?? '---') }}
        </li>
        <li>
            {{ 'Model : ' . ($device->model ?? '---') }}
        </li>
        <li>
            {{ 'Description : ' . ($device->description ?? '---') }}
        </li>
        <li>
            {{ 'LAN : ' . ($device->lan ?? '---') }}
        </li>
        <li>
            {{ 'WiFi : ' . ($device->wifi ?? '---') }}
        </li>
    </ul>
@endsection
