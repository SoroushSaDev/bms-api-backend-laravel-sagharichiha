@php
    use Carbon\Carbon;
    $title = __('Add Register');
@endphp
@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="flex justify-between items-center">
        <a href="{{ route('devices.registers', $device) }}"
           class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
            Back
        </a>
        <h2 class="text-gray-900 dark:text-gray-100 text-3xl">
            {{ $title }}
        </h2>
        <div></div>
    </div>
    <hr class="my-5 border-gray-300 dark:border-gray-700">
    <form action="{{ route('registers.store', $device) }}" method="post">
        @csrf
        <div class="grid gap-6 mb-6 md:grid-cols-3">
            <div>
                <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Title
                </label>
                <input type="text" id="title" name="title" required
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                />
            </div>
            <div>
                <label for="unit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Unit
                </label>
                <input type="text" id="unit" name="unit"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                />
            </div>
            <div>
                <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Type
                </label>
                <input type="text" id="type" name="type"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                />
            </div>
        </div>
        <hr class="my-5 border-gray-300 dark:border-gray-700">
        <button type="submit"
                class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            Submit
        </button>
    </form>
@endsection
