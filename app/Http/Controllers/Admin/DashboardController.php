<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalArticles = Article::count();
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();

        $recentArticles = Article::with('author')->latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
        
            'totalArticles',
            'totalUsers',
            'totalAdmins',
            'recentArticles',
            'recentUsers'
        ));
    }
}