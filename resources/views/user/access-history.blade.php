<x-app-layout>
    <x-user.side-bar></x-user.side-bar>

    <div class="grid grid-cols-12 text-gray-900 min-h-fit pr-4">
        <div class="col-start-3 col-end-13">
            <div class="p-4 flex justify-between items-center">
                <h1 class="text-3xl">Access History</h1>

            </div>



{{--                <form class="w-[400px]" action="" method="get">--}}
{{--                    <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>--}}
{{--                    <div class="relative">--}}
{{--                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">--}}
{{--                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">--}}
{{--                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>--}}
{{--                            </svg>--}}
{{--                        </div>--}}
{{--                        <input type="search" id="search" name="search_query" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search users..." value="{{ $search }}" />--}}
{{--                        <button id="searchBtn" type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>--}}
{{--                    </div>--}}
{{--                </form>--}}


{{--                <a href="{{route('admin.users.showCreateForm')}}" class="rounded bg-blue-700 text-center text-white px-3 h-full py-2 ">New user</a>--}}
{{--            </div>--}}


            <div class="p-4 flex justify-between items-center">
                <div class="flex gap-1 items-center w-[50%]">
                    <select name="year" id="year" class="w-[30%] border-2 border-gray-400 rounded py-1">
                            <option value="" selected></option>
                        @foreach($years as $year)
                            @if($year != $currentYear)
                                <option value="{{$year}}">{{$year}}</option>
                            @else
                                <option value="{{$currentYear}}" selected>{{$currentYear}}</option>
                            @endif
                        @endforeach
                    </select>
                    <select name="month" id="month" class="w-[20%] border-2 border-gray-400 rounded py-1">
                            <option value=""></option>
                        @for($month = 1; $month <= 12; $month++)
                            @if($month != $currentMonth)
                                <option value="{{$month}}">{{\Carbon\Carbon::create()->month($month)->format('F')}}</option>
                            @else
                                <option value="{{$month}}" selected>{{\Carbon\Carbon::create()->month($month)->format('F')}}</option>
                            @endif
                        @endfor
                    </select>
                    <select name="day" id="day" class="w-[10%] border-2 border-gray-400 rounded py-1">
                            <option value=""></option>
                        @for($day = 1; $day <= 31; $day++)
                            @if($day != $currentDay)
                                <option value="{{$day}}">{{ $day }}</option>
                            @else
                                <option value="{{$day}}" selected>{{$day}}</option>
                            @endif
                        @endfor
                    </select>
                    <button id="timeFilter" class="bg-blue-500 text-white px-2 py-1 rounded">Search</button>
                </div>
                <div class="flex items-center border-2 border-gray-500 bg-white w-fit ml-4 p-4 shadow-md">
                    <input id="login" name="types[]" type="checkbox" value="login"
                           {{ in_array('login', request('types', [])) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="login" class="ms-2 text-sm font-medium text-gray-900">Login</label>

                    <input id="logout" name="types[]" type="checkbox" value="logout"
                           {{ in_array('logout', request('types', [])) ? 'checked' : '' }}
                           class="ml-3 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="logout" class="ms-2 text-sm font-medium text-gray-900">Logout</label>
                </div>

            </div>
            <div class="px-3 py-4 flex flex-col justify-between h-3/4">
                <table class="w-full text-md bg-white shadow-md rounded mb-4 border-t-2">
                    <thead>
                    <tr class="border-b">
                        <th class="text-left p-3 px-5">
                            <div class="inline-block">Type</div>

                        </th>
                        <th class="text-left p-3 px-5">
                            <div class="inline-block">Ip Address</div>
                        </th>
                        <th class="text-left p-3 px-5">Browser</th>
                        <th class="text-left p-3 px-5">Platform</th>
                        <th class="text-left p-3 px-5">Device</th>
                        <th class="text-left p-3 px-5">
                            <div class="inline-block">Time</div>
                            <button class="sort-btn" data-order="asc" id="time">
                                <svg class="w-3 h-3 text-gray-800 dark:text-white inline transition-transform duration-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 8">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 5.326 5.7a.909.909 0 0 0 1.348 0L13 1"/>
                                </svg>
                            </button>
                        </th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($histories as $history)
                        <tr class="border-b bg-gray-100">
                            <td class="p-3 px-5">
                                {{$history->type}}
                            </td>
                            <td class="p-3 px-5">
                                {{$history->ip_address}}
                            </td>
                            <td class="p-3 px-5">
                                {{$history->browser}}
                            </td>
                            <td class="p-3 px-5">
                                {{$history->platform}}
                            </td>
                            <td class="p-3 px-5">
                                {{$history->device}}
                            </td>
                            <td class="p-3 px-5">
                                {{$history->time}}
                            </td>

                    @endforeach
                    </tbody>
                </table>
                {{$histories->appends(request()->query())->links()}}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {


                const urlParams = new URLSearchParams(window.location.search);

                console.log({{ $currentYear .  $currentMonth . $currentDay }})


                const currentSort = urlParams.get('sort') || 'desc';
                const currentField = urlParams.get('field') || 'time';

                // Update all sort icons initially
                document.querySelectorAll('.sort-btn').forEach(button => {
                    const svg = button.querySelector('svg');
                    if (button.id === currentField) {
                        svg.classList.toggle('rotate-180', currentSort === 'desc');
                        button.setAttribute('data-order', currentSort);
                    }
                });


                // Handle filtering
                const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        const newParams = new URLSearchParams();

                        // Handle roles checkboxes
                        const typeCheckboxes = document.querySelectorAll('input[name="types[]"]:checked');
                        const types = Array.from(typeCheckboxes).map(cb => cb.value);

                        // Add roles if selected
                        if (types.length > 0) {
                            types.forEach(type => newParams.append('types[]', type));
                        }

                        // Preserve current sorting and query
                        const currentParams = new URLSearchParams(window.location.search);
                        if (currentParams.has('field') && currentParams.has('sort')) {
                            newParams.set('field', currentParams.get('field'));
                            newParams.set('sort', currentParams.get('sort'));
                        }

                        if (currentParams.has('year')) {
                            newParams.set('year', currentParams.get('year'));
                        }
                        if (currentParams.has('month')) {
                            newParams.set('month', currentParams.get('month'));
                        }
                        if (currentParams.has('day')) {
                            newParams.set('day', currentParams.get('day'));
                        }

                        window.location.href = `{{route("user.history")}}?${newParams.toString()}`;
                    });
                });

                {{--    // Handle sorting--}}
                document.querySelectorAll('.sort-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const newParams = new URLSearchParams(window.location.search);
                        const field = this.id;
                        let sort = 'asc';

                        if (field === currentField) {
                            sort = currentSort === 'asc' ? 'desc' : 'asc';
                        }

                        newParams.set('field', field);
                        newParams.set('sort', sort);

                        newParams.delete('page');

                        window.location.href = `{{route("user.history")}}?${newParams.toString()}`;
                    });
                });

                document.querySelector('#timeFilter').addEventListener('click', () => {
                    const year = document.querySelector('#year').value.trim();
                    const month = document.querySelector('#month').value.trim();
                    const day = document.querySelector('#day').value.trim();

                    const newParams = new URLSearchParams(window.location.search);

                    newParams.delete('page');

                    newParams.delete('year');
                    newParams.delete('month');
                    newParams.delete('day');

                    // Only set parameters if they follow the hierarchical rule
                    if (day) {
                        if (!month || !year) {
                        } else {
                            newParams.set('year', year);
                            newParams.set('month', month);
                            newParams.set('day', day);

                            window.location.href = `{{route("user.history")}}?${newParams.toString()}`;
                        }
                    } else if (month) {
                        if (!year) {

                        } else {
                        newParams.set('month', month);
                        newParams.set('year', year);
                        window.location.href = `{{route("user.history")}}?${newParams.toString()}`;
                        }
                    } else if (year) {
                        newParams.set('year', year);
                        window.location.href = `{{route("user.history")}}?${newParams.toString()}`;
                    } else {
                        window.location.href = `{{route("user.history")}}`;
                    }

                });
            });
        </script>
    @endpush
</x-app-layout>
