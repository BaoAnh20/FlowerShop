<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // Tự động truyền biến $categories cho tất cả các view có chứa navbar hoặc layouts chung
    View::composer('*', function ($view) {
        $view->with('categories', Category::where('is_active', true)->get());
    });
    }
}
