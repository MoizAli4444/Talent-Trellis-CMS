<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\UserRegistered;
use App\Listeners\SendWelcomeEmail;
use App\Models\Post;
use App\Observers\PostObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserRegistered::class => [
            SendWelcomeEmail::class,
        ],
    ];

    /**
     * Register any events and observers.
     */
    public function boot()
    {
        parent::boot();

        // Register Post Observer
        Post::observe(PostObserver::class);
    }
}
