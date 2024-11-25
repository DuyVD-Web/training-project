<x-app-layout>

    <x-user.side-bar>
    </x-user.side-bar>
    <div class="grid grid-cols-4 mt-6 gap-6 py-10">
        <form action="{{route('user.info.update')}}" class="col-start-2 col-end-4 relative" id="info-form" method="POST">
            @csrf
            <button id="editButton" type="button" class="absolute top-0 right-0 text-gray-500 hover:text-blue-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
            </button>
            <h3 class="mb-4 text-lg font-medium leading-none text-gray-900 dark:text-white">User Detail</h3>
            <div class="flex w-[75%] gap-4 mb-4 flex-col">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                    <input readonly  type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{$user['name']}}" >
                    @error('name')
                    <div class="text-red-400 m-0">{{$message}}</div>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input readonly  type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{$user['email']}}" >
                </div>
                <div>
                    <label for="phone_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone number</label>
                    <input readonly  type="number" name="phone_number" id="phone_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{$user['phone_number']}}" >
                    @error('phone_number')
                    <div class="text-red-400 m-0">{{$message}}</div>
                    @enderror
                </div>
                <div>
                    <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                    <input readonly  type="text" name="address" id="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{$user['address']}}">
                </div>


            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Save
            </button>
            <button id="cancel-button" class="ml-3 text-gray-900 bg-white hover:text-blue-700 hover:border-blue-700 border focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Cancel
            </button>
        </form>
        <div class=" col-start-2 col-end-4 h-[2px] bg-gray-300"></div>
        <div class="col-start-2">
            <a href="{{route('user.info.changeEmail')}}" class="px-3 py-2 rounded bg-blue-700 text-white"> Change email</a>
        </div>

        <div class=" col-start-2 col-end-4 h-[2px] bg-gray-300"></div>
        <form action="{{route('user.info.password')}}" class="col-start-2 col-end-4 " method="post">
            @csrf
            <h3 class="mb-4 text-lg font-medium leading-none text-gray-900 dark:text-white">Change Password</h3>
            <div class="flex w-[75%] gap-4 mb-4 flex-col">

                <div>
                    <label for="current_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current Password</label>
                    <input type="password" name="current_password" id="current_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New Password</label>
                    <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>

                @error('password')
                    <div class="text-red-400 mb-4">{{ $message }}</div>
                @enderror
                @error('current_password')
                <div class="text-red-400 mb-4">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Change password
            </button>
        </form>
    </div>
    @push('scripts')
        <script>

            const userInfos = {
                'name': '{{$user['name']}}',
                'email': '{{$user['email']}}',
                'phone_number': '{{$user['phone_number']}}',
                'address': '{{$user['address']}}',
            };

            const editButton = document.querySelector('#editButton');
            const inputs = document.querySelectorAll('#info-form input');
            const saveButton = document.querySelector('#info-form button[type="submit"]');
            const cancelButton = document.querySelector('#cancel-button');

            saveButton.style.display = 'none';
            cancelButton.style.display = 'none';

            editButton.addEventListener('click', () => {
                inputs.forEach(input => {
                    if (input.id !== "email") {
                        input.readOnly = false;
                    }
                });

                saveButton.style.display =  'inline-flex';
                cancelButton.style.display = 'inline-flex';
            });

            cancelButton.addEventListener('click', (e) => {
                e.preventDefault();
                inputs.forEach(input => {
                    if (input.id !== "email") {
                        input.readOnly = true;
                        input.value = userInfos[input.id];
                    }
                });

                saveButton.style.display =  'none';
                cancelButton.style.display = 'none';
            })


        </script>
    @endpush
</x-app-layout>
