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

    @foreach ($categories as $category)
    <url>
        <loc>{{ route('kategori.detail', $category->slug) }}</loc>
        <lastmod>{{ ($category->updated_at ?? $generatedAt)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    @foreach ($products as $product)
    <url>
        <loc>{{ route('produk.detail', $product->slug) }}</loc>
        <lastmod>{{ ($product->updated_at ?? $generatedAt)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>
