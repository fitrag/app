@php
    $schema = [];
    $currentRoute = Route::currentRouteName();
    $siteTitle = \App\Models\Setting::get('site_title', config('app.name', 'Laravel'));
    $siteUrl = url('/');
    $logoUrl = asset('storage/logo.png'); // Assuming a logo exists, or use a default

    // Global WebSite Schema
    $webSiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $siteTitle,
        'url' => $siteUrl,
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => route('blog.search') . '?q={search_term_string}',
            'query-input' => 'required name=search_term_string'
        ]
    ];
    $schema[] = $webSiteSchema;

    // Single Post Schema
    if ($currentRoute === 'blog.show' && isset($post)) {
        $articleSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('blog.show', $post->slug)
            ],
            'headline' => $post->title,
            'description' => Str::limit(strip_tags($post->content), 160),
            'image' => $post->image ? (str_starts_with($post->image, 'http') ? $post->image : asset('storage/' . $post->image)) : [],
            'author' => [
                '@type' => 'Person',
                'name' => $post->user->name,
                'url' => route('profile.show', $post->user->id)
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $siteTitle,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $logoUrl
                ]
            ],
            'datePublished' => $post->created_at->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String()
        ];
        $schema[] = $articleSchema;
        
        // Breadcrumb for Post
        $breadcrumbSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => $siteUrl
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $post->category->name,
                    'item' => route('blog.category', $post->category->slug)
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $post->title,
                    'item' => route('blog.show', $post->slug)
                ]
            ]
        ];
        $schema[] = $breadcrumbSchema;
    }

    // Archive Pages (Category/Tag)
    if (($currentRoute === 'blog.category' || $currentRoute === 'blog.tag') && isset($posts)) {
        $collectionSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $title ?? 'Archive',
            'description' => 'Archive of posts for ' . ($title ?? ''),
            'url' => url()->current(),
            'mainEntity' => [
                '@type' => 'ItemList',
                'itemListElement' => $posts->map(function($post, $index) {
                    return [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'url' => route('blog.show', $post->slug),
                        'name' => $post->title
                    ];
                })->toArray()
            ]
        ];
        $schema[] = $collectionSchema;
        
        // Breadcrumb for Archive
        $breadcrumbSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => $siteUrl
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $title ?? 'Archive',
                    'item' => url()->current()
                ]
            ]
        ];
        $schema[] = $breadcrumbSchema;
    }

    // Profile Page
    if ($currentRoute === 'profile.show' && isset($user)) {
        $profileSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'ProfilePage',
            'mainEntity' => [
                '@type' => 'Person',
                'name' => $user->name,
                'description' => $user->bio,
                'image' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                'url' => route('profile.show', $user->id)
            ]
        ];
        $schema[] = $profileSchema;
    }
@endphp

@foreach($schema as $entity)
<script type="application/ld+json">
    {!! json_encode($entity, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endforeach
