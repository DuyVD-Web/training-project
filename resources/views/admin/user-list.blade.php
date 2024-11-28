<x-app-layout>
    <x-admin.side-bar></x-admin.side-bar>

    @if(session('success'))
        <div id="toast-success" class="flex fixed top-[80px] right-14 items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
                <span class="sr-only">Check icon</span>
            </div>
            <div class="ms-3 text-sm font-normal">{{session('success')}}</div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-success" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>
    @endif


    @if(session('error'))
        <div id="toast-danger" class="flex fixed top-[80px] right-14 items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                </svg>
                <span class="sr-only">Error icon</span>
            </div>
            <div class="ms-3 text-sm font-normal">{{session('error')}}</div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-danger" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>
    @endif
    <div class="grid grid-cols-12 text-gray-900 min-h-fit pr-4">
        <div class="col-start-3 col-end-13">
            <div class="p-4 flex justify-between items-center">
                <h1 class="text-3xl">Users</h1>

                <div>
                    <form action="{{route('admin.users.import')}}" method="POST" enctype="multipart/form-data" class="space-y-4 max-w-md mx-auto">
                        @method('PUT')
                        @csrf
                        <input
                            type="file"
                            name="file"
                            class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-green-50 file:text-green-700
                            hover:file:bg-green-100"
                        >
                        <button class="flex items-center justify-center w-full py-2 px-4 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                            <i class="fa fa-file mr-2"></i> Import User Data
                        </button>
                    </form>
                </div>

            </div>

            <div class="p-4 flex justify-between items-center">
                <div class="flex items-center border-2 border-gray-500 bg-white w-fit ml-4 p-4 shadow-md">
                    <input id="admin" name="roles[]" type="checkbox" value="admin"
                           {{ in_array('admin', request('roles', [])) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="admin" class="ms-2 text-sm font-medium text-gray-900">Admin</label>

                    <input id="user" name="roles[]" type="checkbox" value="user"
                           {{ in_array('user', request('roles', [])) ? 'checked' : '' }}
                           class="ml-3 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="user" class="ms-2 text-sm font-medium text-gray-900">User</label>

                    <input id="verified" name="verified" type="checkbox" value="1"
                           {{ request('verified') ? 'checked' : '' }}
                           class="ml-3 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="verified" class="ms-2 text-sm font-medium text-gray-900">Verified</label>
                </div>

                <form class="w-[400px]" action="" method="get">
                    <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="search" id="search" name="search_query" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search users..." value="{{ $search }}" />
                        <button id="searchBtn" type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
                    </div>
                </form>


                <a href="{{route('admin.users.showCreateForm')}}" class="rounded bg-blue-700 text-center text-white px-3 h-full py-2 ">New user</a>
            </div>

            <div class="px-3 py-4 flex flex-col justify-between h-3/4">
                <table class="w-full text-md bg-white shadow-md rounded mb-4 border-t-2">
                    <thead>
                    <tr class="border-b">
                        <th class="text-left p-3 px-5">
                            <div class="inline-block">Name</div>
                            <button class="sort-btn" data-order="asc" id="name">
                                <svg class="w-3 h-3 text-gray-800 dark:text-white inline transition-transform duration-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 8">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 5.326 5.7a.909.909 0 0 0 1.348 0L13 1"/>
                                </svg>
                            </button>
                        </th>
                        <th class="text-left p-3 px-5">
                            <div class="inline-block">Email</div>
                            <button class="sort-btn" data-order="asc" id="email">
                                <svg class="w-3 h-3 text-gray-800 dark:text-white inline transition-transform duration-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 8">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 5.326 5.7a.909.909 0 0 0 1.348 0L13 1"/>
                                </svg>
                            </button>
                        </th>
                        <th class="text-left p-3 px-5">
                            <div class="inline-block">Role</div>
                            <button class="sort-btn" data-order="asc" id="role">
                                <svg class="w-3 h-3 text-gray-800 dark:text-white inline transition-transform duration-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 8">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 5.326 5.7a.909.909 0 0 0 1.348 0L13 1"/>
                                </svg>
                            </button>
                        </th>
                        <th class="text-left p-3 px-5">
                            <div class="inline-block">Verified at</div>
                            <button class="sort-btn" data-order="asc" id="email_verified_at">
                                <svg class="w-3 h-3 text-gray-800 dark:text-white inline transition-transform duration-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 8">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 5.326 5.7a.909.909 0 0 0 1.348 0L13 1"/>
                                </svg>
                            </button>
                        </th>
                        <th class="text-left p-3 px-5">Phone number</th>
                        <th class="text-left p-3 px-5">Address</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr class="border-b bg-gray-100">
                            <td class="p-3 px-5">
                                {{$user->name}}
                            </td>
                            <td class="p-3 px-5">
                                {{$user->email}}
                            </td>
                            <td class="p-3 px-5">
                                {{$user->role}}
                            </td>
                            <td class="p-3 px-5">
                                {{$user->email_verified_at}}
                            </td>
                            <td class="p-3 px-5">
                                {{$user->phone_number}}
                            </td>
                            <td class="p-3 px-5">
                                {{$user->address}}
                            </td>
                            <td class="p-3 px-5 flex justify-end gap-3">
                                @if($user->role !== 'admin')
                                    <form action="{{route('admin.users.delete', $user)}}" method="post"
                                          onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @method('DELETE')
                                        @csrf
                                        <button
                                           class="text-sm bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Delete
                                        </button>
                                    </form>
                                    <a href="{{route('admin.users.showEdit', $user)}}"
                                       class="text-sm bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">
                                        Edit
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{$users->appends(request()->query())->links()}}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);

                // default
                const currentField = urlParams.get('field') || 'name';
                const currentSort = urlParams.get('sort') || 'asc';

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
                    checkbox.addEventListener('change', function() {
                        const newParams = new URLSearchParams();

                        // Handle roles checkboxes
                        const roleCheckboxes = document.querySelectorAll('input[name="roles[]"]:checked');
                        const roles = Array.from(roleCheckboxes).map(cb => cb.value);

                        // Add roles if selected
                        if (roles.length > 0) {
                            roles.forEach(role => newParams.append('roles[]', role));
                        }

                        // Handle verified checkbox
                        const verifiedCheckbox = document.querySelector('input[name="verified"]');
                        if (verifiedCheckbox.checked) {
                            newParams.set('verified', '1');
                        }

                        // Preserve current sorting and query
                        const currentParams = new URLSearchParams(window.location.search);
                        if (currentParams.has('field') && currentParams.has('sort')) {
                            newParams.set('field', currentParams.get('field'));
                            newParams.set('sort', currentParams.get('sort'));
                        }
                        if (currentParams.has('search_query')) {
                            newParams.set('search_query', currentParams.get('search_query'));
                        }

                        window.location.href = `{{route("admin.users")}}?${newParams.toString()}`;
                    });
                });

                // Handle sorting
                document.querySelectorAll('.sort-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const newParams = new URLSearchParams(window.location.search);
                        const field = this.id;
                        let sort = 'asc';

                        if (field === currentField) {
                            sort = currentSort === 'asc' ? 'desc' : 'asc';
                        }

                        newParams.set('field', field);
                        newParams.set('sort', sort);

                        newParams.delete('page');

                        window.location.href = `{{route("admin.users")}}?${newParams.toString()}`;
                    });
                });

                const toast = document.getElementById('toast-success');
                if (toast) {
                    toast.querySelector('button').addEventListener('click', ()=> {
                        toast.remove();
                    })
                    setTimeout(() => {
                        toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                        setTimeout(() => {
                            toast.remove();
                        }, 500);
                    }, 3000);
                }
            });
        </script>
    @endpush
</x-app-layout>
