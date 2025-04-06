<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;


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
        Paginator::useBootstrapFive();
        Paginator::useBootstrapFour();
        View::composer('*', function ($view) {
            $cartItemCount = 0;
            if (Auth::check()) {
                $cartItemCount = Cart::where('user_id', Auth::id())
                    ->sum('quantity'); 
            }
            $view->with('cartItemCount', $cartItemCount);
        });
    }
    protected $namespace = 'App\\Http\\Controllers';

}
