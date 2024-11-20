<x-app-layout>

    <x-user.side-bar>
    </x-user.side-bar>
    <div class="grid grid-cols-4 mt-6 gap-6 py-10">
        <form action="#" class="col-start-2 col-end-4 relative" id="info-form" method="POST">
            @csrf
            <h3 class="mb-4 text-lg font-medium leading-none text-gray-900 dark:text-white">Change email</h3>
            <div class="flex w-[75%] gap-4 mb-4 flex-col">
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
                </div>
                @error('email')
                <div class="text-red-400 m-0">{{$message}}</div>
                @enderror
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                @error('password')
                <div class="text-red-400 m-0">{{$message}}</div>
                @enderror
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Change
            </button>
        </form>
    </div>
</x-app-layout>
