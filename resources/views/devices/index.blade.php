@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
@section('title', __('Devices'))
@section('content')
    <div class="flex justify-between items-center">
        <div></div>
        <h2 class="text-gray-900 dark:text-gray-100 text-3xl">
            {{ __('Devices') }}
        </h2>
        <a href="{{ route('devices.create') }}"
           class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Add Device
        </a>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-900 dark:text-gray-100">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    #
                </th>
                <th scope="col" class="px-6 py-3">
                    User
                </th>
                <th scope="col" class="px-6 py-3">
                    Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Type
                </th>
                <th scope="col" class="px-6 py-3">
                    Brand
                </th>
                <th scope="col" class="px-6 py-3">
                    Model
                </th>
                <th scope="col" class="px-6 py-3">
                    LAN
                </th>
                <th scope="col" class="px-6 py-3">
                    WiFi
                </th>
                <th scope="col" class="px-6 py-3">
                    Created At
                </th>
                <th scope="col" class="px-6 py-3">
                    Updated At
                </th>
                <th scope="col" class="px-6 py-3">
                    Actions
                </th>
            </tr>
            </thead>
            <tbody>
            @forelse($devices as $key => $device)
                <tr class="odd:bg-dar-gray-100 odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td class="px-6 py-4">
                        {{ $key + 1 }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $device->User?->name ?? '---' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $device->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $device->type ?? '---' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $device->brand ?? '---' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $device->model ?? '---' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $device->lan ?? '---' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $device->wifi ?? '---' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ Carbon::parse($device->created_at)->format('Y/m/d | H:m:i') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ Carbon::parse($device->updated_at)->format('Y/m/d | H:m:i') }}
                    </td>
                    <td class="px-6 py-4 flex">
                        <a href="{{ route('devices.show', $device) }}"
                           class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Show
                        </a>
                        <a href="{{ route('devices.edit', $device) }}"
                           class="ml-5 text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                            Edit
                        </a>
                        <form action="{{ route('devices.destroy', $device) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="ml-5 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Remove
                            </button>
                        </form>
                        <a href="{{ route('devices.registers', $device) }}"
                            class="ml-5 text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800">
                            Registers
                        </a>
                    </td>
                </tr>
            @empty
                <tr class="odd:bg-dar-gray-100 odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td colspan="11" class="px-6 py-4">
                        No Records
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
