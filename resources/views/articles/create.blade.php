<x-app-layout>
    <x-slot name="header">
        <div class="md:flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Article') }}
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

                @if ($errors->any())
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data" onsubmit="return handleFormSubmit(event)">
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title: <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label for="audio_video_url" class="block text-gray-700 text-sm font-bold mb-2">Audio/Video URL:</label>
                        <input type="url" id="audio_video_url" name="audio_video_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Thumbnail Image: <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center">
                            <input type="radio" id="upload_option" name="thumbnail_option" value="upload" checked class="mr-2" required>
                            <label for="upload_option" class="mr-4">
                                Upload
                            </label>
                            <input type="radio" id="url_option" name="thumbnail_option" value="url" class="mr-2" required>
                            <label for="url_option">
                                URL
                            </label>
                        </div>
                        <div id="upload_input" class="mt-2">
                            <input type="file" id="thumbnail_image_file" name="thumbnail_image_file" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div id="url_input" class="mt-2 hidden">
                            <input type="text" id="thumbnail_image_url" name="thumbnail_image_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                    </div>

                    <!-- posted by, auth name as display and hidden value -->
                    <div class="mb-4">
                        <label for="posted_by" class="block text-gray-700 text-sm font-bold mb-2">Posted By:</label>
                        <input type="text" id="posted_by" name="posted_by" value="{{ Auth::user()->name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-500 bg-gray-200 leading-tight focus:outline-none focus:shadow-outline" disabled>
                        <input type="hidden" name="posted_by" value="{{ Auth::user()->name }}">
                    </div>

                    <div class="mb-4">
                        <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content: <span class="text-red-500">*</span></label>
                        <div id="editor" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" style="height: 300px;"></div>
                        <input type="hidden" id="content" name="content">
                        <div class="mt-2 text-gray-600 text-sm">
                            <span id="charCount">0</span> characters, <span id="wordCount">0</span> words
                        </div>
                        <div class="text-red-500 text-sm mt-2" id="error" style="display: none;">
                            Content must be at least 100 characters and 10 words long.
                        </div>
                    </div>

                    <div class="mb-4">
                        <input type="hidden" name="status" value="pending">
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Submit
                        </button>
                    </div>
                </form>

                <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
                <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var quill = new Quill('#editor', {
                            theme: 'snow'
                        });

                        function updateCounts() {
                            const text = quill.getText().trim();
                            const words = text.split(/\s+/).filter(Boolean);

                            document.getElementById('charCount').innerText = text.length;
                            document.getElementById('wordCount').innerText = words.length;

                            if (text.length >= 100 && words.length >= 10) {
                                document.getElementById('error').style.display = 'none';
                            } else {
                                document.getElementById('error').style.display = 'block';
                            }
                        }

                        quill.on('text-change', updateCounts);

                        window.handleFormSubmit = function(event) {
                            var contentInput = document.querySelector('input[name=content]');
                            contentInput.value = quill.root.innerHTML.trim(); 

                            const text = quill.getText().trim(); 
                            const words = text.split(/\s+/).filter(Boolean); 

                            if (text.length < 100 || words.length < 10) {
                                alert('Content must be at least 100 characters and 10 words long.');
                                return false; // Prevent form submission
                            }

                            var thumbnailOption = document.querySelector('input[name="thumbnail_option"]:checked').value;
                            var fileInput = document.getElementById('thumbnail_image_file');
                            var urlInput = document.getElementById('thumbnail_image_url');

                            if (thumbnailOption === 'upload' && fileInput.files.length === 0) {
                                alert('Please upload a thumbnail image.');
                                return false; // Prevent form submission
                            }

                            if (thumbnailOption === 'url' && !urlInput.value.trim()) {
                                alert('Please provide a URL for the thumbnail image.');
                                return false; // Prevent form submission
                            }

                            return true; // Allow form submission
                        };

                        var uploadOption = document.getElementById('upload_option');
                        var urlOption = document.getElementById('url_option');
                        var uploadInput = document.getElementById('upload_input');
                        var urlInput = document.getElementById('url_input');

                        uploadOption.addEventListener('change', function() {
                            if (uploadOption.checked) {
                                uploadInput.classList.remove('hidden');
                                urlInput.classList.add('hidden');
                            }
                        });

                        urlOption.addEventListener('change', function() {
                            if (urlOption.checked) {
                                uploadInput.classList.add('hidden');
                                urlInput.classList.remove('hidden');
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>