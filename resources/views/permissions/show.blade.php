@php
    use Carbon\Carbon;
    $title = $permission->name;
@endphp
@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="flex justify-between items-center">
        @include('shared.button', ['type' => 'back', 'url' => route('permissions.index')])
        @include('shared.title', [$title])
        @include('shared.fake-div')
    </div>
    <hr class="my-5 border-gray-300 dark:border-gray-700">
    <ul class="space-y-5">
        <li>
            Name : {{ $permission->name }}
        </li>
        <li>
            {{ 'Created At : ' . Carbon::parse($permission->created_at)->format('Y/m/d | H:m:i') }}
        </li>
        <li>
            {{ 'Updated At : ' . Carbon::parse($permission->updated_at)->format('Y/m/d | H:m:i') }}
        </li>
    </ul>
    <hr class="my-5 border-gray-300 dark:border-gray-700">
    <div class="flex">
        @include('shared.button', ['type' => 'edit', 'url' => route('permissions.edit', $permission)])
        @include('shared.button', ['type' => 'delete', 'url' => route('permissions.destroy', $permission)])
    </div>
@endsection
