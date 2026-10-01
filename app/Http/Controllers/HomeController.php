<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->take(6)
            ->get();

        $featuredProducts = Product::with(['images', 'category', 'variants'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        $giftProducts = Product::with(['images', 'category', 'variants'])
            ->where('is_active', true)
            ->whereHas('category', function ($q) {
                $q->where('slug', 'regalos');
            })
            ->take(4)
            ->get();

        $intimateProducts = Product::with(['images', 'category', 'variants'])
            ->where('is_active', true)
            ->where('target_audience', 'moraia_intimo')
            ->take(4)
            ->get();

        $manifesto = Setting::get('about_manifesto', 'Moraia existe para reunir en una sola caja todo lo que una mujer necesita para consentirse.');

        return view('pages.home', compact(
            'categories',
            'featuredProducts',
            'giftProducts',
            'intimateProducts',
            'manifesto'
        ));
    }
}
