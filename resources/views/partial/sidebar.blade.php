@php
    use Illuminate\Support\Facades\Route;
   $routeName = Route::currentRouteName();
   $left = $dir == 'RTL' ? 'right-' : 'left-';
   $sidebar = $dir == 'RTL' ? 'right' : 'left';
@endphp
<button type="button" id="sidebar" data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar"
        aria-controls="default-sidebar" data-drawer-placement="{{ $sidebar }}"
        class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
    <span class="sr-only">
        Open sidebar
    </span>
    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path clip-rule="evenodd" fill-rule="evenodd"
              d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
        </path>
    </svg>
</button>
<aside id="default-sidebar"
       class="fixed top-0 {{ $left }}0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
       aria-label="Sidebar">
    <div
        class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800 sm:m{{ $dir == 'RTL' ? 'r' : 'l' }}-5 sm:mt-5 rounded">
        <p class="flex items-center ps-2.5">
            <span class="self-center text-4xl font-semibold whitespace-nowrap dark:text-white">
                {{ __('sidebar.brand') }}
            </span>
        </p>
        <hr class="my-5 border-gray-300 dark:border-gray-700">
        <ul class="space-y-2 font-medium">
            @guest
                <li>
                    <a href="{{ route('login') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-gray-100 {{ CheckClass('link', 'login', $routeName) }} group">
                        <svg
                            class="flex-shrink-0 w-5 h-5 {{ CheckClass('svg', 'login', $routeName) }} transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Login</span>
                    </a>
                </li>
            @endguest
            @if(auth()->check())
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-gray-100 {{ CheckClass('link', 'dashboard', $routeName) }} group">
                        <svg
                            class="flex-shrink-0 w-5 h-5 {{ CheckClass('svg', 'dashboard', $routeName) }} transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 22 21">
                            <path
                                d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                            <path
                                d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                        </svg>
                        <span class="ms-3">
                            {{ __('sidebar.dashboard') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('projects.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-gray-100 {{ CheckClass('link', 'projects.index', $routeName) }} group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-briefcase-fill flex-shrink-0 w-5 h-5 {{ CheckClass('svg', 'projects.index', $routeName) }} transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                             viewBox="0 0 16 16">
                            <path
                                d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5"/>
                            <path
                                d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85z"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">
                        {{ __('sidebar.projects') }}
                    </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('devices.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-gray-100 {{ CheckClass('link', 'devices.index', $routeName) }} group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-cpu-fill flex-shrink-0 w-5 h-5 {{ CheckClass('svg', 'devices.index', $routeName) }} transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                             viewBox="0 0 16 16">
                            <path d="M6.5 6a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/>
                            <path
                                d="M5.5.5a.5.5 0 0 0-1 0V2A2.5 2.5 0 0 0 2 4.5H.5a.5.5 0 0 0 0 1H2v1H.5a.5.5 0 0 0 0 1H2v1H.5a.5.5 0 0 0 0 1H2v1H.5a.5.5 0 0 0 0 1H2A2.5 2.5 0 0 0 4.5 14v1.5a.5.5 0 0 0 1 0V14h1v1.5a.5.5 0 0 0 1 0V14h1v1.5a.5.5 0 0 0 1 0V14h1v1.5a.5.5 0 0 0 1 0V14a2.5 2.5 0 0 0 2.5-2.5h1.5a.5.5 0 0 0 0-1H14v-1h1.5a.5.5 0 0 0 0-1H14v-1h1.5a.5.5 0 0 0 0-1H14v-1h1.5a.5.5 0 0 0 0-1H14A2.5 2.5 0 0 0 11.5 2V.5a.5.5 0 0 0-1 0V2h-1V.5a.5.5 0 0 0-1 0V2h-1V.5a.5.5 0 0 0-1 0V2h-1zm1 4.5h3A1.5 1.5 0 0 1 11 6.5v3A1.5 1.5 0 0 1 9.5 11h-3A1.5 1.5 0 0 1 5 9.5v-3A1.5 1.5 0 0 1 6.5 5"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">
                        {{ __('sidebar.devices') }}
                    </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('cities.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-gray-100 {{ CheckClass('link', 'cities.index', $routeName) }} group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-map-fill flex-shrink-0 w-5 h-5 {{ CheckClass('svg', 'cities.index', $routeName) }} transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                             viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                  d="M16 .5a.5.5 0 0 0-.598-.49L10.5.99 5.598.01a.5.5 0 0 0-.196 0l-5 1A.5.5 0 0 0 0 1.5v14a.5.5 0 0 0 .598.49l4.902-.98 4.902.98a.5.5 0 0 0 .196 0l5-1A.5.5 0 0 0 16 14.5zM5 14.09V1.11l.5-.1.5.1v12.98l-.402-.08a.5.5 0 0 0-.196 0zm5 .8V1.91l.402.08a.5.5 0 0 0 .196 0L11 1.91v12.98l-.5.1z"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">
                        {{ __('sidebar.cities') }}
                    </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-gray-100 {{ CheckClass('link', 'users.index', $routeName) }} group">
                        <svg
                            class="flex-shrink-0 w-5 h-5 {{ CheckClass('svg', 'users.index', $routeName) }} transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 18">
                            <path
                                d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">
                            {{ __('sidebar.users') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('roles.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-gray-100 {{ CheckClass('link', 'roles.index', $routeName) }} group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-person-lines-fill flex-shrink-0 w-5 h-5 {{ CheckClass('svg', 'roles.index', $routeName) }} transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                             viewBox="0 0 16 16">
                            <path
                                d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">
                            {{ __('sidebar.roles') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('permissions.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-gray-100 {{ $routeName == 'permissions.index' ? 'bg-blue-500 hover:bg-blue-600' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }} group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-shield-lock-fill flex-shrink-0 w-5 h-5 {{ $routeName == 'permissions.index' ? 'text-gray-900 dark:text-gray-100' : 'text-gray-500 dark:text-gray-400' }} transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                             viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                  d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.8 11.8 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7 7 0 0 0 1.048-.625 11.8 11.8 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.54 1.54 0 0 0-1.044-1.263 63 63 0 0 0-2.887-.87C9.843.266 8.69 0 8 0m0 5a1.5 1.5 0 0 1 .5 2.915l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99A1.5 1.5 0 0 1 8 5"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">
                            {{ __('sidebar.permissions') }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('translations.index') }}"
                       class="flex items-center p-2 text-gray-900 rounded-lg dark:text-gray-100 {{ CheckClass('link', 'translations.index', $routeName) }} group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-translate flex-shrink-0 w-5 h-5 {{ CheckClass('svg', 'translations.index', $routeName) }} transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white"
                             viewBox="0 0 16 16">
                            <path
                                d="M4.545 6.714 4.11 8H3l1.862-5h1.284L8 8H6.833l-.435-1.286zm1.634-.736L5.5 3.956h-.049l-.679 2.022z"/>
                            <path
                                d="M0 2a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v3h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-3H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zm7.138 9.995q.289.451.63.846c-.748.575-1.673 1.001-2.768 1.292.178.217.451.635.555.867 1.125-.359 2.08-.844 2.886-1.494.777.665 1.739 1.165 2.93 1.472.133-.254.414-.673.629-.89-1.125-.253-2.057-.694-2.82-1.284.681-.747 1.222-1.651 1.621-2.757H14V8h-3v1.047h.765c-.318.844-.74 1.546-1.272 2.13a6 6 0 0 1-.415-.492 2 2 0 0 1-.94.31"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">
                        {{ __('sidebar.translations') }}
                    </span>
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <a class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-red-100 dark:hover:bg-red-700 group">
                            <button type="submit" class="w-full flex items-center">
                                <svg
                                    class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 18 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
                                </svg>
                                <span class="ms-3 whitespace-nowrap">
                                    {{ __('sidebar.logout') }}
                                </span>
                            </button>
                        </a>
                    </form>
                </li>
            @endif
        </ul>
    </div>
</aside>
<script>
    setTimeout(function () {
        $('#sidebar').click().click();
    }, 1000);
</script>
