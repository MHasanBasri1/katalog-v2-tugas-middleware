{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
        <lastmod>{{ ($siteLastmod ?? $generatedAt)->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('katalog') }}</loc>
        <lastmod>{{ ($siteLastmod ?? $generatedAt)->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('kategori') }}</loc>
        <lastmod>{{ ($siteLastmod ?? $generatedAt)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('blog.index') }}</loc>
        <lastmod>{{ ($siteLastmod ?? $generatedAt)->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>

    @foreach ($categories as $category)
    @if($category->slug)
    <url>
        <loc>{{ route('kategori.detail', $category->slug) }}</loc>
        <lastmod>{{ ($category->updated_at ?? $generatedAt)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endif
    @endforeach

    @foreach ($products as $product)
    @if($product->slug)
    <url>
        <loc>{{ route('produk.detail', $product->slug) }}</loc>
        <lastmod>{{ ($product->updated_at ?? $generatedAt)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endif
    @endforeach

    @foreach ($blogs ?? [] as $blog)
    <url>
        <loc>{{ route('blog.detail', $blog->slug) }}</loc>
        <lastmod>{{ ($blog->updated_at ?? $generatedAt)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    @foreach ($staticPages ?? [] as $page)
    <url>
        <loc>{{ route('halaman.show', $page->slug) }}</loc>
        <lastmod>{{ ($page->updated_at ?? $generatedAt)->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
</urlset>
