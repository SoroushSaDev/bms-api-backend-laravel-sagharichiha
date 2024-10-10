@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
@section('title', $device->name)
@section('content')
    <div class="flex justify-between items-center">
        <a href="{{ route('devices.index') }}"
           class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
            Back
        </a>
        <h2 class="text-gray-900 dark:text-gray-100 text-3xl">
            {{ $device->name }}
        </h2>
        <div></div>
    </div>
    <hr class="my-5">
    <ul class="space-y-5">
        <li>
            {{ 'User : ' . ($device->User?->FullName() ?? '---') }}
        </li>
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
        <li>
            {{ 'Created At : ' . Carbon::parse($device->created_at)->format('Y/m/d | H:m:i') }}
        </li>
        <li>
            {{ 'Updated At : ' . Carbon::parse($device->updated_at)->format('Y/m/d | H:m:i') }}
        </li>
    </ul>
@endsection
