<?php

namespace App\Providers;

use App\Services\AppService;
use App\Services\DocumentService;
use App\Services\ExamService;
use App\Services\NotificationService;
use App\Services\PostService;
use App\Services\QuestionService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(AppService::class, UserService::class);
        $this->app->bind(AppService::class, NotificationService::class);
        $this->app->bind(AppService::class, QuestionService::class);
        $this->app->bind(AppService::class, PostService::class);
        $this->app->bind(AppService::class, ExamService::class);
        $this->app->bind(AppService::class, DocumentService::class);
    }
}
