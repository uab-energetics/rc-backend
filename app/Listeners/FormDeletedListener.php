<?php

namespace App\Listeners;

use App\Events\FormDeleted;
use App\Services\Forms\FormService;
use App\Services\ProjectForms\ProjectFormService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FormDeletedListener {

    public function __construct(ProjectFormService $projectFormService) {
        $this->projectFormService = $projectFormService;
    }

    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle(FormDeleted $event) {
        $form = $event->form;
        $this->projectFormService->handleFormDeleted($form);
    }
}
