<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Article') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl m-4 mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="mx-auto p-6">
                <form action="{{ route('articles.update', $article->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" class="form-input w-full" required>
                        @error('title')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content</label>
                        <textarea id="content" name="content" rows="4" class="form-input w-full" required>{{ old('content', $article->content) }}</textarea>
                        @error('content')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <input type="hidden" name="status" value="pending">

                    <div class="flex items-center justify-between">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 transition duration-300">{{ __('Update Article') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>