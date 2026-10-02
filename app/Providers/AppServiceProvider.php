<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Document;
use App\Models\Offer;
use App\Models\Project;
use App\Models\Service;
use App\Models\Team;
use App\Models\Video;
use App\Observers\ProfileCompletionObserver;
use App\Services\Admin\SettingService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        require_once app_path('helpers.php');
    }
    
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Team::observe(ProfileCompletionObserver::class);
        Service::observe(ProfileCompletionObserver::class);
        Project::observe(ProfileCompletionObserver::class);
        Document::observe(ProfileCompletionObserver::class);
        Video::observe(ProfileCompletionObserver::class);
        Article::observe(ProfileCompletionObserver::class);
        Offer::observe(ProfileCompletionObserver::class);

        View::composer('admin.partials.sidebar', function ($view) {
            $view->with('adminModules', app(SettingService::class)->moduleFlags());
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
