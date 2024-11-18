<x-app-layout>
    @auth
        You're logged in!
        <form action="/logout" method="post">
            @csrf
            @method('DELETE')
            <button class="font-medium text-primary-600 hover:underline dark:text-primary-500">Log out</button>
        </form>
    @endauth
    @guest
            <a href="/login" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Log in</a>
    @endguest
</x-app-layout>
