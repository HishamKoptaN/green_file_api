<?php

namespace App\Providers;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Social\Post\Post;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}
    public function boot(): void
    {
        Relation::morphMap([
            'poll' => \App\Models\Social\Post\Poll::class,
            'post' => \App\Models\Social\Post\Post::class,
            'sharedPost' => \App\Models\Social\Post\SharedPost::class,
            'occasion' => \App\Models\Social\Post\Occasion::class,
        ]);
    }
}
