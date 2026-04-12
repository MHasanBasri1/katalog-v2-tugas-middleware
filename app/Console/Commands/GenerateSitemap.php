<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use App\Models\StaticPage;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap as a static file for SEO performance.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemap = Sitemap::create();

        // Static Pages (Hardcoded)
        $sitemap->add(Url::create(route('home'))->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap->add(Url::create(route('katalog'))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap->add(Url::create(route('blog.index'))->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap->add(Url::create(route('kategori'))->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

        // Products
        Product::query()->where('status', true)->lazy()->each(function (Product $product) use ($sitemap) {
            $sitemap->add(Url::create(route('produk.detail', $product->slug))
                ->setLastModificationDate($product->updated_at)
                ->setPriority(0.8)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        });

        // Categories
        Category::query()->lazy()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(Url::create(route('kategori.detail', $category->slug))
                ->setLastModificationDate($category->updated_at)
                ->setPriority(0.7)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        });

        // Blog Posts
        Blog::query()->where('is_published', true)->lazy()->each(function (Blog $blog) use ($sitemap) {
            $sitemap->add(Url::create(route('blog.detail', $blog->slug))
                ->setLastModificationDate($blog->updated_at)
                ->setPriority(0.6)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        });

        // Static CMS Pages
        StaticPage::query()->where('is_published', true)->lazy()->each(function (StaticPage $page) use ($sitemap) {
            $sitemap->add(Url::create(route('halaman.show', $page->slug))
                ->setLastModificationDate($page->updated_at)
                ->setPriority(0.5)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}
