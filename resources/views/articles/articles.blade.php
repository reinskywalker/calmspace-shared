<x-app-layout>
    <x-slot name="header">
        <div class="md:flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Your Articles') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl m-4 mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="mx-auto">

                <section class="text-gray-600 body-font">
                    <div class="container px-5 py-5 mx-auto">
                        <nav class="flex space-x-4 mt-2 md:mt-0">
                            <a href="{{ route('articles.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 transition duration-300">{{ __('Create New Article') }}</a>
                        </nav>
                    </div>
                </section>

            </div>
        </div>
    </div>
</x-app-layout>