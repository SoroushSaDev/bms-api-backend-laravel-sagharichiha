@php
    use Carbon\Carbon;
    $title = $city->name;
@endphp
@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="flex justify-between items-center">
        @include('shared.button', ['type' => 'back', 'url' => route('cities.index')])
        @include('shared.title', [$title])
        @include('shared.fake-div')
    </div>
    <hr class="my-5 border-gray-300 dark:border-gray-700">
    <ul class="space-y-5">
        <li>
            Name : {{ $city->name }}
        </li>
        <li>
            Country : {{ $city->Country->name }}
        </li>
        <li>
            {{ 'Created At : ' . Carbon::parse($city->created_at)->format('Y/m/d | H:m:i') }}
        </li>
        <li>
            {{ 'Updated At : ' . Carbon::parse($city->updated_at)->format('Y/m/d | H:m:i') }}
        </li>
    </ul>
    <hr class="my-5 border-gray-300 dark:border-gray-700">
    <div class="flex">
        @include('shared.button', ['type' => 'edit', 'url' => route('cities.edit', $city)])
        @include('shared.button', ['type' => 'delete', 'url' => route('cities.destroy', $city)])
    </div>
@endsection
