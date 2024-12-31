<x-app-layout>
    <x-admin.side-bar></x-admin.side-bar>
    @if(session('success'))
        <div id="toast-success"
             class="flex fixed top-[80px] right-14 items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
             role="alert">
            <div
                class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                     viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
                <span class="sr-only">Check icon</span>
            </div>
            <div class="ms-3 text-sm font-normal">{{session('success')}}</div>
            <button type="button"
                    class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    data-dismiss-target="#toast-success" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>
    @endif


    @if(session('error'))
        <div id="toast-danger"
             class="flex fixed top-[80px] right-14 items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
             role="alert">
            <div
                class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                     viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                </svg>
                <span class="sr-only">Error icon</span>
            </div>
            <div class="ms-3 text-sm font-normal">{{session('error')}}</div>
            <button type="button"
                    class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    data-dismiss-target="#toast-danger" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>
    @endif


    <div class="grid grid-cols-12 mt-6 gap-6 py-10">
        <h1 class=" col-start-3 col-end-11 mb-4 text-lg font-medium leading-none text-gray-900 dark:text-white">Permission manage</h1>

        <div class="flex items-start overflow-auto col-start-3 col-end-12 mr-8 w-full gap-10">
            @foreach($roles as $role)
                <div class="bg-white shadow-md rounded-lg mb-4 overflow-hidden w-2/5">
                    <div class="bg-gray-100 px-4 py-3 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-700">
                            {{ ucfirst($role->name) }} Role Permissions
                        </h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.role-management.update') }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <input type="hidden" name="role_id" value="{{ $role->id }}">

                            <div class="flex flex-col gap-2">
                                @php
                                    $roleType = $role->name === \App\Enums\UserRole::User
                                        ? \App\Enums\UserRole::User
                                        : \App\Enums\UserRole::Admin;

                                    $filteredPermissions = $allPermissions->filter(function ($permission) use ($roleType) {
                                        return explode('.', $permission->name)[0] === $roleType;
                                    });
                                @endphp

                                @foreach($filteredPermissions as $permission)
                                    <div class="flex items-center">
                                        <input
                                            type="checkbox"
                                            name="permissions[]"
                                            value="{{ $permission->id }}"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            id="permission_{{ $role->id }}_{{ $permission->id }}"
                                            @checked($role->permissions->contains($permission))
                                        >
                                        <label
                                            for="permission_{{ $role->id }}_{{ $permission->id }}"
                                            class="ml-2 block text-sm text-gray-900"
                                        >
                                            {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <button
                                type="submit"
                                class="mt-4 w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                Update Permissions
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
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

                const toastDanger = document.getElementById('toast-danger');
                if (toastDanger) {
                    toastDanger.querySelector('button').addEventListener('click', ()=> {
                        toastDanger.remove();
                    })
                    setTimeout(() => {
                        toastDanger.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                        setTimeout(() => {
                            toastDanger.remove();
                        }, 500);
                    }, 3000);
                }

                document.querySelectorAll('form').forEach(form => {
                    form.addEventListener('submit', (event) => {
                        event.preventDefault()
                        const buttons = document.querySelectorAll('form button');
                        buttons.forEach(button => {
                            button.disabled = true;
                        });

                        event.target.submit();
                    });
                });
            })
        </script>
    @endpush

</x-app-layout>
