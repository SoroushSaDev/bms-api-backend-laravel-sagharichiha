@php
    use Carbon\Carbon;
    $title = __('device.index');
@endphp
@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="flex justify-between items-center">
        @include('shared.fake-div')
        @include('shared.title', [$title])
        @include('shared.button', ['type' => 'add', 'url' => route('devices.create'), 'label' => __('device.add')])
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-900 dark:text-gray-100">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    #
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('table.user') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('table.name') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('table.type') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('table.brand') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('table.model') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    LAN
                </th>
                <th scope="col" class="px-6 py-3">
                    WiFi
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('table.created_at') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('table.updated_at') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('table.actions') }}
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
                        @include('shared.button', ['type' => 'show', 'url' => route('devices.show', $device)])
                        @include('shared.button', ['type' => 'edit', 'url' => route('devices.edit', $device)])
                        @include('shared.button', ['type' => 'delete', 'url' => route('devices.destroy', $device)])
                        @include('shared.button', ['type' => 'extra', 'url' => route('devices.registers', $device), 'label' => __('device.registers')])
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
    @include('partial.pagination', ['items' => $devices])
@endsection
