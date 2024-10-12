@php
    use Carbon\Carbon;
    $title = __('Projects');
@endphp
@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="flex justify-between items-center">
        <div></div>
        <h2 class="text-gray-900 dark:text-gray-100 text-3xl">
            {{ $title }}
        </h2>
        <a href="{{ route('projects.create') }}"
           class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Add Project
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
                    Name
                </th>
                <th scope="col" class="px-6 py-3">
                    User
                </th>
                <th scope="col" class="px-6 py-3">
                    City
                </th>
                <th scope="col" class="px-6 py-3">
                    Devices Count
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
                        <a href="{{ route('projects.show', $project) }}"
                           class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Show
                        </a>
                        <a href="{{ route('projects.edit', $project) }}"
                           class="ml-5 text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                            Edit
                        </a>
                        <form action="{{ route('projects.destroy', $project) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="ml-5 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Remove
                            </button>
                        </form>
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
@endsection
