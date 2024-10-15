@php
    use Carbon\Carbon;
    $title = translate($device->name);
@endphp
@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="flex justify-between items-center">
        @include('shared.button', ['type' => 'back', 'url' => route('devices.index')])
        @include('shared.title', [$title])
        @include('shared.fake-div')
    </div>
    <hr class="my-5 border-gray-300 dark:border-gray-700">
    <form action="{{ route('devices.update', $device) }}" method="post">
        @csrf
        @method('PATCH')
        <div class="grid gap-6 mb-6 md:grid-cols-4">
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Name
                </label>
                <input type="text" id="name" name="name" required
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       value="{{ $device->name }}"/>
            </div>
            <div>
                <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Type
                </label>
                <input type="text" id="type" name="type"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       value="{{ $device->type }}"/>
            </div>
            <div>
                <label for="brand"
                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Brand
                </label>
                <input type="text" id="brand" name="brand"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       value="{{ $device->brand }}"/>
            </div>
            <div>
                <label for="model" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Model
                </label>
                <input type="text" id="model" name="model"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       value="{{ $device->model }}"/>
            </div>
        </div>
        <div class="mb-6">
            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Description
            </label>
            <textarea id="description" name="description"
                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      rows="4">{{ $device->description }}</textarea>
        </div>
        <div class="grid gap-6 mb-6 md:grid-cols-4">
            <div>
                <label for="topic" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Topic
                </label>
                <input type="text" id="topic" name="topic"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       value="{{ $device->mqtt_topic }}"/>
            </div>
            <div>
                <label for="lan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    LAN
                </label>
                <input type="text" id="lan" name="lan"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       value="{{ $device->lan }}"/>
            </div>
            <div>
                <label for="wifi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    WiFi
                </label>
                <input type="text" id="wifi" name="wifi"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       value="{{ $device->wifi }}"/>
            </div>
        </div>
        <hr class="my-5 border-gray-300 dark:border-gray-700">
        <button type="submit"
                class="flex items-center justify-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                 class="bi bi-arrow-up-circle-fill mr-2" viewBox="0 0 16 16">
                <path
                    d="M16 8A8 8 0 1 0 0 8a8 8 0 0 0 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
            </svg>
            Update
        </button>
    </form>
@endsection
