<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = app()->make('sitemap');
        $sitemap->setCache('laravel.sitemap', 60); // کش به مدت 60 دقیقه

        if (!$sitemap->isCached()) {

            $sitemap->add(
                url('/sitemap-posts'),
                now()->toAtomString(), // تاریخ جاری
                '0.9',
                'daily'
            );

            $sitemap->add(
                url('/sitemap-pages'),
                now()->toAtomString(),
                '0.9',
                'daily'
            );

            $sitemap->add(
                url('/sitemap-products'),
                now()->toAtomString(),
                '0.9',
                'daily'
            );

            // اضافه کردن دسته‌بندی‌های محصولات
            $sitemap->add(
                url('/sitemap-category-products'),
                now()->toAtomString(),
                '0.9',
                'daily'
            );
        }

        return $sitemap->render('xml');
    }


    public function posts()
    {
        $sitemap = app()->make('sitemap');
        $sitemap->setCache('laravel.sitemap.posts', 60);

        if (!$sitemap->isCached()) {
            $posts = Post::published()->latest('updated_at')->get();
            foreach ($posts as $post) {
                $sitemap->add(
                    route('front.posts.show', ['post' => $post]),
                    $post->updated_at,
                    '0.9',
                    'weekly'
                );
            }
        }

        return $sitemap->render('xml');
    }

    public function pages()
    {
        $sitemap = app()->make('sitemap');
        $sitemap->setCache('laravel.sitemap.pages', 60);

        if (!$sitemap->isCached()) {
            $pages = Page::where('published', true)->latest('updated_at')->get();
            foreach ($pages as $page) {
                $sitemap->add(
                    route('front.pages.show', ['page' => $page]),
                    $page->updated_at,
                    '0.9',
                    'weekly'
                );
            }
        }

        return $sitemap->render('xml');
    }

    public function products()
    {
        $sitemap = app()->make('sitemap');
        $sitemap->setCache('laravel.sitemap.products', 60);

        if (!$sitemap->isCached()) {

            $products = Product::published()->latest('updated_at')->get();

            foreach ($products as $product) {
                $sitemap->add(
                    route('front.products.show', ['product' => $product]),
                    $product->updated_at,
                    '0.9',
                    'daily'
                );
            }
        }

        return $sitemap->render('xml');

    }

    public function category_products()
    {
        $sitemap = app()->make('sitemap');
        $sitemap->setCache('laravel.sitemap.category_products', 60);

        if (!$sitemap->isCached()) {

            $categories = Category::whereHas('products') // بررسی وجود محصول متصل
            ->published()
            ->latest('updated_at')
            ->get();


            foreach ($categories as $category) {
                $sitemap->add(
                    route('front.products.category-products',  $category),
                    $category->updated_at,
                    '0.9',
                    'daily'
                );
            }
        }

        return $sitemap->render('xml');
    }
}
