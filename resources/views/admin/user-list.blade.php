<x-app-layout>
    <x-admin.side-bar></x-admin.side-bar>
    <div class="grid grid-cols-12 text-gray-900 bg-gray-200 h-[90vh]">
        <div class="col-start-3 col-end-12">
            <div class="p-4 flex justify-between">
                <h1 class="text-3xl">Users</h1>
                <a href="{{route('admin.users.showCreateForm')}}" class="rounded bg-blue-700 text-white px-3 py-1">New user</a>
            </div>
            <div class="px-3 py-4 flex flex-col justify-between h-3/4">
                <table class="w-full text-md bg-white shadow-md rounded mb-4">
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
                                {{$user->phone_number}}
                            </td>
                            <td class="p-3 px-5">
                                {{$user->address}}
                            </td>
                            <td class="p-3 px-5 flex justify-end gap-3">
                                <form action="{{route('admin.users.delete', $user)}}" method="post"
                                      onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @method('DELETE')
                                    @csrf
                                    <button
                                       class="text-sm bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Delete
                                    </button>
                                </form>
                                <a href="#"
                                   class="text-sm bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">
                                    Edit
                                </a>
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
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);

                const order = document.querySelector('#{{$field}}');
                order.querySelector('svg').classList.add("{{$sort == 'asc' ? 'r' :"rotate-180" }}");
                order.setAttribute('data-order', '{{$sort == 'asc' ? 'asc' :"desc" }}');

                const sortButtons = document.querySelectorAll('.sort-btn');

                sortButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        const svg = this.querySelector('svg');
                        const currentOrder = this.getAttribute('data-order');
                        const field = this.id; // Use the button's ID directly

                        // Toggle the rotation and order
                        if (currentOrder === 'asc') {
                            svg.classList.remove('hidden'); // Show SVG for ascending
                            svg.innerHTML = `<path d="M17 10l-5-5-5 5h10z" />`; // Up arrow
                            this.setAttribute('data-order', 'desc');
                            window.location.href = `{{route("admin.users")}}?${urlParams.has('page') ? `page=${urlParams.get('page')}&` : ''}field=${field}&sort=desc`;
                        } else {
                            svg.classList.remove('hidden'); // Show SVG for descending
                            svg.innerHTML = `<path d="M17 14l-5 5-5-5h10z" />`; // Down arrow
                            this.setAttribute('data-order', 'asc');
                            window.location.href = `{{route("admin.users")}}?${urlParams.has('page') ? `page=${urlParams.get('page')}&` : ''}field=${field}&sort=asc`;
                        }

                        // Here you would typically add your sorting logic
                        console.log(`Sorting by ${this.closest('th').textContent} in ${this.getAttribute('data-order')} order`);
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
