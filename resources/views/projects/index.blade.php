@php
    use Carbon\Carbon;
    $title = __('project.index');
@endphp
@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="flex justify-between items-center">
        @include('shared.fake-div')
        @include('shared.title', [$title])
        @include('shared.button', ['type' => 'add', 'url' => route('projects.create'), 'label' => __('project.add')])
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
                    {{ __('table.user') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('table.city') }}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{ __('table.devices_count') }}
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
            @forelse($projects as $key => $project)
                <tr class="odd:bg-dar-gray-100 odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td class="px-6 py-4">
                        {{ $key + 1 }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $project->name ?? '---' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $project->User?->name ?? '---' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $project->City?->name ?? '---' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $project->Devices->count() }}
                    </td>
                    <td class="px-6 py-4">
                        {{ Carbon::parse($project->created_at)->format('Y/m/d | H:m:i') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ Carbon::parse($project->updated_at)->format('Y/m/d | H:m:i') }}
                    </td>
                    <td class="px-6 py-4 flex">
                        @include('shared.button', ['type' => 'show', 'url' => route('projects.show', $project)])
                        @include('shared.button', ['type' => 'edit', 'url' => route('projects.edit', $project)])
                        @include('shared.button', ['type' => 'delete', 'url' => route('projects.destroy', $project)])
                    </td>
                </tr>
            @empty
                <tr class="odd:bg-dar-gray-100 odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td colspan="8" class="px-6 py-4">
                        No Records
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @include('partial.pagination', ['items' => $projects])
@endsection
