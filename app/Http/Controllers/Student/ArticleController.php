<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    // Menampilkan daftar artikel untuk siswa
    public function index(Request $request)
    {
        $search = $request->input('search');

        $articles = Article::with('author')
            ->whereNotNull('published_at')
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                             ->orWhere('content', 'like', "%{$search}%");
            })
            ->latest('published_at')
            ->paginate(6)
            ->withQueryString();

        return view('student.articles.index', compact('articles', 'search'));
    }

    // Menampilkan detail artikel lengkap
    public function show($slug)
    {
        $article = Article::with('author')
            ->where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        // Mengambil artikel lain untuk rekomendasi bacaan selanjutnya (sidebar/bawah)
        $relatedArticles = Article::where('id', '!=', $article->id)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('student.articles.show', compact('article', 'relatedArticles'));
    }
}