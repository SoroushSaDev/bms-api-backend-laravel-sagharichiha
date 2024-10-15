@php
    use Carbon\Carbon;
    $title = __('permission.index');
@endphp
@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="flex justify-between items-center">
        @include('shared.fake-div')
        @include('shared.title', [$title])
        @include('shared.button', ['type' => 'add', 'url' => route('permissions.create'), 'label' => __('permission.add')])
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
            @forelse($permissions as $key => $permission)
                <tr class="odd:bg-dar-gray-100 odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td class="px-6 py-4">
                        {{ $key + 1 }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $permission->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ Carbon::parse($permission->created_at)->format('Y/m/d | H:m:i') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ Carbon::parse($permission->updated_at)->format('Y/m/d | H:m:i') }}
                    </td>
                    <td class="px-6 py-4 flex">
                        @include('shared.button', ['type' => 'show', 'url' => route('permissions.show', $permission)])
                        @include('shared.button', ['type' => 'edit', 'url' => route('permissions.edit', $permission)])
                        @include('shared.button', ['type' => 'delete', 'url' => route('permissions.destroy', $permission)])
                    </td>
                </tr>
            @empty
                <tr class="odd:bg-dar-gray-100 odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td colspan="5" class="px-6 py-4">
                        No Records
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @include('partial.pagination', ['items' => $permissions])
@endsection
