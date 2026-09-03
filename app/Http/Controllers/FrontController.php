<?php

namespace App\Http\Controllers;

use App\Models\ArticleNews;
use App\Models\Author;
use App\Models\BannerAdvertisment;
use App\Models\Category;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $articles = ArticleNews::with(['category'])
            ->where('is_featured', 'not_featured')
            ->latest()
            ->take(6)
            ->get();

        $featured_articles = ArticleNews::with(['category'])
            ->where('is_featured', 'featured')
            ->inRandomOrder()
            ->take(3)
            ->get();

        $authors = Author::all();

        $bannerads = BannerAdvertisment::where('is_active', 'active')
            ->where('type', 'banner')
            ->inRandomOrder()
            ->first();

        // Makeup Section
        $makeup_featured_article = ArticleNews::whereHas('category', function ($query) {
            $query->where('slug', 'makeup');
        })->where('is_featured', true)->latest()->first();

        $makeup_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('slug', 'makeup');
        })
        ->when($makeup_featured_article, function ($q) use ($makeup_featured_article) {
            $q->where('id', '!=', $makeup_featured_article->id);
        })
        ->latest()
        ->take(10)
        ->get();

        // Skincare Section
        $skincare_featured_article = ArticleNews::whereHas('category', function ($query) {
            $query->where('slug', 'skincare');
        })->where('is_featured', true)->latest()->first();

        $skincare_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('slug', 'skincare');
        })->latest()->take(10)->get();

        // Bodycare Section
        $bodycare_featured_article = ArticleNews::whereHas('category', function ($query) {
            $query->where('slug', 'bodycare');
        })->where('is_featured', true)->latest()->first();

        $bodycare_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('slug', 'bodycare');
        })
        ->when($bodycare_featured_article, function ($q) use ($bodycare_featured_article) {
            $q->where('id', '!=', $bodycare_featured_article->id);
        })
        ->latest()
        ->take(10)
        ->get();

        return view('front.index', compact(
            'makeup_featured_article',
            'makeup_articles',
            'skincare_featured_article',
            'skincare_articles',
            'bodycare_featured_article',
            'bodycare_articles',
            'categories',
            'articles',
            'authors',
            'featured_articles',
            'bannerads'
        ));
    }

    // 📂 Halaman kategori
    public function category(Category $category)
{
    $categories = Category::all();
    $bannerads = BannerAdvertisment::where('is_active', 'active')
        ->where('type', 'banner')
        ->inRandomOrder()
        ->first();

    // Featured article khusus kategori ini
    $featured_article = ArticleNews::where('category_id', $category->id)
        ->where('is_featured', true)
        ->latest()
        ->first();

    // Semua artikel kategori ini kecuali featured
    $articles = ArticleNews::where('category_id', $category->id)
        ->when($featured_article, function($q) use ($featured_article) {
            $q->where('id', '!=', $featured_article->id);
        })
        ->latest()
        ->get();

    return view('front.category', compact(
        'category',
        'categories',
        'bannerads',
        'featured_article',
        'articles'
    ));


    }

    // ✍️ Halaman penulis
    public function author(Author $author)
    {
        $categories = Category::all();
        $bannerads = BannerAdvertisment::where('is_active', 'active')
            ->where('type', 'banner')
            ->inRandomOrder()
            ->first();

        return view('front.author', compact('categories', 'author', 'bannerads'));
    }

    // 🔍 Halaman pencarian artikel
    public function search(Request $request)
    {
        $request->validate([
            'keyword' => ['required', 'string', 'max:255'],
        ]);

        $categories = Category::all();
        $keyword = $request->keyword;

        $articles = ArticleNews::with(['category', 'author'])
            ->where('name', 'like', '%' . $keyword . '%')
            ->paginate(6);

        return view('front.search', compact('articles', 'keyword', 'categories'));
    }

    public function details(ArticleNews $article_news)
    {
        $categories = Category::all();

        $articleNews = $article_news->load(['author', 'category']);

        $author_news = ArticleNews::with(['category'])
            ->where('author_id', $articleNews->author_id)
            ->where('id', '!=', $articleNews->id)
            ->latest()
            ->take(3)
            ->get();

        $articles = ArticleNews::with(['category'])
            ->where('id', '!=', $articleNews->id)
            ->latest()
            ->take(3)
            ->get();

        $bannerads = BannerAdvertisment::where('type', 'banner')->first();
        $square_ads_1 = BannerAdvertisment::where('type', 'square_1')->first();
        $square_ads_2 = BannerAdvertisment::where('type', 'square_2')->first();

        return view('front.details', compact(
            'articleNews',
            'categories',
            'author_news',
            'articles',
            'bannerads',
            'square_ads_1',
            'square_ads_2'
        ));
    }
}