<?php

namespace App\Providers;

use App\Events\EncodingCreated;
use App\Events\FormDeleted;
use App\Events\FormQuestionRemoved;
use App\Listeners\EncodingCreatedListener;
use App\Listeners\FormDeletedListener;
use App\Listeners\FormQuestionRemovedListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\EncodingChanged' => [
            'App\Listeners\RunConflictScan',
        ],
        'App\Events\CommentUpdate' => [
            'App\Listeners\CommentsListener'
        ],
        FormQuestionRemoved::class => [
            FormQuestionRemovedListener::class,
        ],
        EncodingCreated::class => [
            EncodingCreatedListener::class,
        ],
        FormDeleted::class => [
            FormDeletedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
