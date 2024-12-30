<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

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
        Paginator::useBootstrap();

        if (config('app.env') === 'production') {
            // config('app.url') が https 始まりか？http 始まりか？ をもとに強制的に scheme を設定する
            URL::forceScheme(Str::startsWith(config('app.url'), 'https') ? 'https' : 'http');
        }

        // viewのパスをbladeで拾いたいためフィルターを定義。
        // JSでviewのパスを使用したい
        view()->composer('*', function ($view) {
            $view_name = str_replace('.', '/', $view->getName());
            view()->share('view_name', $view_name);
        });

    }
}
