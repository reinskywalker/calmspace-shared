<x-app-layout>
    <x-slot name="header">
        <div class="md:flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Article Detail') }}
            </h2>
        </div>
    </x-slot>

    <!-- main article -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="mx-auto p-6">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $article->title }}</h1>
                    <p class="text-gray-600 mb-4">Posted by: {{ $article->posted_by }} on {{ $article->created_at->format('F d, Y') }}</p>
                    <div class="mb-6 flex justify-center">
                        <img src="{{ asset($article->thumbnail_image_url) }}" alt="{{ $article->title }}" class="w-64 h-full object-cover rounded-lg shadow-md">
                    </div>
                    <div class="prose max-w-full text-gray-700 mb-6">
                        <!-- Render HTML content safely -->
                        {!! $article->content !!}
                    </div>

                    @if ($article->audio_video_url)
                        <div class="mb-6 embed-responsive-16by9">
                            @if (Str::contains($article->audio_video_url, ['youtube.com', 'youtu.be']))
                                @php
                                    $video_id = '';
                                    if (Str::contains($article->audio_video_url, 'youtube.com')) {
                                        $url_components = parse_url($article->audio_video_url);
                                        parse_str($url_components['query'], $params);
                                        $video_id = $params['v'] ?? '';
                                    } else {
                                        $video_id = Str::afterLast($article->audio_video_url, '/');
                                    }
                                @endphp
                                @if($video_id)
                                    <div class="relative w-full h-0 pb-[50%] mb-6">
                                        <iframe class="w-full h-full rounded-lg shadow-md" src="https://www.youtube.com/embed/{{ $video_id }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                @else
                                    <p>Invalid YouTube URL</p>
                                @endif
                            @elseif (Str::contains($article->audio_video_url, 'vimeo.com'))
                                @php
                                    $video_id = Str::afterLast($article->audio_video_url, '/');
                                @endphp
                                <div class="relative w-full h-0 pb-[50%] mb-6">
                                    <iframe class="w-full h-full rounded-lg shadow-md" src="https://player.vimeo.com/video/{{ $video_id }}" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            @else
                                <div class="relative w-full h-0 pb-[50%] mb-6">
                                    <video controls class="absolute top-0 left-0 w-full h-full rounded-lg shadow-md">
                                        <source src="{{ $article->audio_video_url }}" type="video/mp4">
                                        Your browser does not support the video element.
                                    </video>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-500 text-center">No video available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- discussion pane -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @if(Auth::check())
        <section class="bg-white dark:bg-gray-900 py-8 lg:py-16 antialiased">
            <div class="max-w-2xl mx-auto px-4">
                <!-- Discussion Header -->
                <h2 class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">Discussion ({{ $article->discussions->count() }})</h2>

                <!-- Comment Form -->
                <form method="POST" action="{{ route('discussions.store') }}" class="mb-6">
                    @csrf
                    <input type="hidden" name="article_id" value="{{ $article->id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <div class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                        <label for="comment" class="sr-only">Your comment</label>
                        <textarea id="comment" name="comment" rows="6" class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800" placeholder="Write a comment..." required></textarea>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                        Post Comment
                    </button>
                </form>

                <!-- Display Comments -->
                @foreach($article->discussions as $discussion)
                <article class="p-6 mb-3 text-base bg-white rounded-lg dark:bg-gray-900 {{ $loop->first ? '' : 'border-t border-gray-200 dark:border-gray-700' }}">
                    <footer class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            @if($discussion->user)
                            <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">
                                <img class="mr-2 w-6 h-6 rounded-full" src="{{ $discussion->user->profile_picture_url ?? 'https://via.placeholder.com/150' }}" alt="{{ $discussion->user->name }}">
                                {{ $discussion->user->name }}
                            </p>
                            @else
                            <p class="text-gray-500 dark:text-gray-400">User information not available.</p>
                            @endif

                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <time pubdate datetime="{{ $discussion->created_at->toDateString() }}" title="{{ $discussion->created_at->format('F j, Y') }}">{{ $discussion->created_at->format('M. j, Y') }}</time>
                            </p>
                        </div>
                        <!-- Optional dropdown for comment actions -->
                        <button id="dropdownComment{{ $discussion->id }}Button" data-dropdown-toggle="dropdownComment{{ $discussion->id }}" class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 dark:text-gray-40 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-700 dark:focus:ring-gray-600" type="button">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 3">
                                <path d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
                            </svg>
                            <span class="sr-only">Comment settings</span>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="dropdownComment{{ $discussion->id }}" class="hidden z-10 w-36 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownComment{{ $discussion->id }}Button">
                                <li>
                                    <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                </li>
                                <li>
                                    <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Remove</a>
                                </li>
                                <li>
                                    <a="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Report</a>
                                </li>
                            </ul>
                        </div>
                    </footer>
                    <p class="text-gray-500 dark:text-gray-400">{{ $discussion->comment }}</p>
                </article>
                @endforeach
            </div>
        </section>

        @else
        <section class="bg-white dark:bg-gray-900 py-8 lg:py-16 antialiased">
            <div class="max-w-2xl mx-auto px-4">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center mb-6">Log in to see the discussion</h2>
                <form class="flex justify-center">
                    <a href="{{ route('login') }}" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 inline-block text-center">
                        Login
                    </a>
                </form>
            </div>
        </section>
        @endif
    </div>
</x-app-layout>
