@extends('components.layouts.public')

@section('content')
    <article class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <!-- Header -->
        <header class="mb-12 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 font-serif tracking-tight mb-6">
                {{ $page->title }}
            </h1>
            <div class="w-16 h-1 bg-gray-900 mx-auto"></div>
        </header>

        <!-- Content -->
        <div class="prose prose-lg sm:prose-xl max-w-none prose-gray font-serif prose-headings:font-sans prose-headings:font-bold prose-a:text-gray-900 prose-a:no-underline prose-a:border-b prose-a:border-gray-300 hover:prose-a:border-gray-900 prose-img:rounded-lg">
            {!! $page->content !!}
        </div>
    </article>
@endsection
