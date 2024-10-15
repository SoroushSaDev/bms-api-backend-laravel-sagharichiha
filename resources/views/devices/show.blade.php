@php
    use Carbon\Carbon;
    $title = translate($device->name);
@endphp
@extends('layouts.app')
@section('title', $device->name)
@section('content')
    <div class="flex justify-between items-center">
        @include('shared.button', ['type' => 'back', 'url' => route('devices.index')])
        @include('shared.title', [$title])
        @include('shared.fake-div')
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
