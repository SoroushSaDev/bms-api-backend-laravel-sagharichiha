@php
    use Carbon\Carbon;
    $title = __('city.index');
@endphp
@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="flex justify-between items-center">
        @include('shared.fake-div')
        @include('shared.title', [$title])
        @include('shared.button', ['type' => 'add', 'url' => route('cities.create'), 'label' => __('city.add')])
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-900 dark:text-gray-100">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    #
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('table.name') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('table.country') }}
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
            @forelse($cities as $key => $city)
                <tr class="odd:bg-dar-gray-100 odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td class="px-6 py-4">
                        {{ $key + 1 }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $city->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $city->Country->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ Carbon::parse($city->created_at)->format('Y/m/d | H:m:i') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ Carbon::parse($city->updated_at)->format('Y/m/d | H:m:i') }}
                    </td>
                    <td class="px-6 py-4 flex">
                        @include('shared.button', ['type' => 'show', 'url' => route('cities.show', $city)])
                        @include('shared.button', ['type' => 'edit', 'url' => route('cities.edit', $city)])
                        @include('shared.button', ['type' => 'delete', 'url' => route('cities.destroy', $city)])
                    </td>
                </tr>
            @empty
                <tr class="odd:bg-dar-gray-100 odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td colspan="6" class="px-6 py-4">
                        No Records
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @include('partial.pagination', ['items' => $cities])
@endsection
