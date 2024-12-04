<x-app-layout>
    <x-slot name="header">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <div class="md:flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Your Articles') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl m-4 mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="mx-auto p-6">

                @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

                @if (session('error'))
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    {{ session('error') }}
                </div>
                @endif

                <section class="text-gray-600 body-font">
                    <div class="container px-5 py-5 mx-auto">
                        <nav class="flex space-x-4 mt-2 md:mt-0 md:ml-auto">
                            <a href="{{ route('articles.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 transition duration-300">{{ __('Create New Article') }}</a>
                        </nav>
                        @if($articles->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">Thumbnail</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">User</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">Title</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">Status</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">Date Posted</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach ($articles as $article)
                                    <tr>
                                        <td class="px-6 py-4 border-b border-gray-500">
                                            <img src="{{ asset($article->thumbnail_image_url) }}" alt="{{ $article->title }}" class="w-24 h-24 object-cover">
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-500">
                                            {{ $article->posted_by }}
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-500">
                                            {{$article->title}}
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-500">
                                            {{ $article->status }}
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-500">
                                            {{ $article->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-500">
                                            <div class="flex space-x-2">
                                                @if($article->status === 'revised' || $article->status === 'pending')
                                                <a href="{{ route('articles.edit', $article->id) }}" class="w-full text-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 transition duration-300">
                                                    {{ __('Edit') }}
                                                </a>
                                                @endif
                                                <button type="button" class="w-full text-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-700 transition duration-300 delete-btn" data-id="{{ $article->id }}">
                                                    {{ __('Delete') }}
                                                </button>
                                            </div>
                                            <form id="delete-form-{{ $article->id }}" action="{{ route('articles.destroy', $article->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>

                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $articles->links() }}
                        </div>
                        @else
                        <div class="p-4 w-full">
                            <div class="border-2 border-gray-200 px-4 py-6 rounded-lg bg-white">
                                <h2 class="title-font font-medium text-2xl text-gray-900">No articles available</h2>
                            </div>
                        </div>
                        @endif
                    </div>
                </section>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const articleId = this.getAttribute('data-id');
                    const form = document.getElementById('delete-form-' + articleId);

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(form.action, {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    },
                                    body: JSON.stringify({
                                        _method: 'DELETE'
                                    }),
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.message) {
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: data.message,
                                            icon: 'success',
                                            timer: 1500,
                                            showConfirmButton: false
                                        }).then(() => {
                                            location.reload();
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                });
                        }
                    });
                });
            });
        });
    </script>

</x-app-layout>