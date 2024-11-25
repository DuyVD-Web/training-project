<x-app-layout>
    <x-admin.side-bar></x-admin.side-bar>
    <div class="grid grid-cols-4 mt-6 gap-6 py-10">
        <form action="{{route('admin.users.update', $user)}}" class="col-start-2 col-end-4 relative" id="info-form" method="POST">
            @csrf
            <h3 class="mb-4 text-lg font-medium leading-none text-gray-900 dark:text-white">Edit user</h3>
            <div class="flex gap-4 mb-4 flex-col">
                <div class="flex justify-between">
                    <div class=" w-[45%]">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                        <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               value="{{$user->name}}" >
                    </div>
                    <div class="w-[45%]">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email
                            @if($user->email_verified_at)
                            <span class="text-green-400"> (Email verified)</span>
                            @else
                            <span class="text-red-300"> (Email not verified)</span>
                            @endif

                        </label>
                        <input readonly type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                value="{{$user->email}}" >
                    </div>
                </div>
                <div class="flex justify-between">
                    <div class="w-[45%]">
                        <label for="phone_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone number</label>
                        <input type="number" name="phone_number" id="phone_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                value="{{$user->phone_number}}" >
                    </div>


                    <div class="w-[45%]">
                            <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                        <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @foreach((new \ReflectionClass(\App\UserRole::class))->getConstants() as $key => $value)
                                <option value="{{ $value }}" {{ $user->role === $value ? 'selected' : '' }}>{{ $key }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                    <input type="text" name="address" id="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           value="{{$user->address}}">
                </div>
{{--                @if($user->email_verified_at)--}}
{{--                    <div class="bg-gray-400 w-full h-[0.5px] my-3"></div>--}}
{{--                    <div>--}}
{{--                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">--}}
{{--                            Send password reset link--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                @endif--}}

            </div>
            <div class="flex justify-end">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Update
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
