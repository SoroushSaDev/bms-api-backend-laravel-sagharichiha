@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
@section('title', __('Devices'))
@section('content')
    <div class="text-center">
        <h2 class="text-gray-900 dark:text-gray-100 text-3xl">
            {{ __('Devices') }}
        </h2>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-900 dark:text-gray-100">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    #
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
                    Description
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
                        {{ $device->description ?? '---' }}
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
                    <td class="px-6 py-4">
{{--                        <a href="{{ route('registers.index') . '?device_id=' . $device->id }}"--}}
{{--                           class="hover:underline hover:text-blue-500">--}}
{{--                            Registers--}}
{{--                        </a>--}}
                        <form action="{{ route('devices.destroy', $device->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                               class="hover:text-red-500">
                                Remove
                            </button>
                        </form>
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
