<?php

namespace App\Providers;

use App\Events\CommentUpdate;
use App\Events\EncodingChanged;
use App\Events\EncodingCreated;
use App\Events\FormDeleted;
use App\Events\FormQuestionRemoved;
use App\Events\UserCreated;
use App\Events\UserDeleted;
use App\Events\UserUpdated;
use App\Listeners\CommentsListener;
use App\Listeners\EncodingCreatedListener;
use App\Listeners\FormDeletedListener;
use App\Listeners\FormQuestionRemovedListener;
use App\Listeners\RunConflictScan;
use App\Listeners\UserCreatedListener;
use App\Listeners\UserDeletedListener;
use App\Listeners\UserUpdatedListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        EncodingChanged::class => [
            RunConflictScan::class,
        ],
        CommentUpdate::class => [
            CommentsListener::class,
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
        UserCreated::class => [
            UserCreatedListener::class,
        ],
        UserUpdated::class => [
            UserUpdatedListener::class,
        ],
        UserDeleted::class => [
            UserDeletedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot() {
        parent::boot();

        //
    }
}
