<!-- Primary Meta Tags -->
<meta name="title" content="{{ $post->title }} - {{ \App\Models\Setting::get('site_title') }}">
<meta name="description" content="{{ Str::limit(strip_tags($post->content), 160) }}">
<meta name="keywords" content="{{ $post->tags->pluck('name')->implode(', ') }}">
<meta name="author" content="{{ $post->user->name }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ route('blog.show', $post->slug) }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="article">
<meta property="og:url" content="{{ route('blog.show', $post->slug) }}">
<meta property="og:title" content="{{ $post->title }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($post->content), 160) }}">
<meta property="og:image" content="{{ $post->image ? (str_starts_with($post->image, 'http') ? $post->image : asset('storage/' . $post->image)) : asset('images/default-og.jpg') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name" content="{{ \App\Models\Setting::get('site_title') }}">
<meta property="article:published_time" content="{{ $post->created_at->toIso8601String() }}">
<meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
<meta property="article:author" content="{{ $post->user->name }}">
<meta property="article:section" content="{{ $post->category->name }}">
@foreach($post->tags as $tag)
<meta property="article:tag" content="{{ $tag->name }}">
@endforeach

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ route('blog.show', $post->slug) }}">
<meta name="twitter:title" content="{{ $post->title }}">
<meta name="twitter:description" content="{{ Str::limit(strip_tags($post->content), 160) }}">
<meta name="twitter:image" content="{{ $post->image ? (str_starts_with($post->image, 'http') ? $post->image : asset('storage/' . $post->image)) : asset('images/default-og.jpg') }}">
<meta name="twitter:creator" content="@{{ $post->user->name }}">

<!-- Additional SEO -->
<meta name="language" content="English">
<meta name="revisit-after" content="7 days">
<meta name="distribution" content="global">
<meta name="rating" content="general">

<!-- Schema.org Structured Data -->
@php
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "Article",
        "headline" => $post->title,
        "description" => Str::limit(strip_tags($post->content), 160),
        "image" => $post->image ? (str_starts_with($post->image, 'http') ? $post->image : asset('storage/' . $post->image)) : '',
        "author" => [
            "@type" => "Person",
            "name" => $post->user->name
        ],
        "publisher" => [
            "@type" => "Organization",
            "name" => \App\Models\Setting::get('site_title'),
            "logo" => [
                "@type" => "ImageObject",
                "url" => asset('logo.png')
            ]
        ],
        "datePublished" => $post->created_at->toIso8601String(),
        "dateModified" => $post->updated_at->toIso8601String(),
        "mainEntityOfPage" => [
            "@type" => "WebPage",
            "@id" => route('blog.show', $post->slug)
        ],
        "articleSection" => $post->category->name,
        "keywords" => $post->tags->pluck('name')->implode(', '),
        "wordCount" => str_word_count(strip_tags($post->content)),
        "articleBody" => strip_tags($post->content)
    ];
@endphp
<script type="application/ld+json">
    {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
