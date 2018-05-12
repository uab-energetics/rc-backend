<?php

namespace App\Listeners;

use App\Events\EncodingCreated;
use App\Services\Encodings\EncodingService;
use App\Services\Forms\FormService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EncodingCreatedListener {
    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle(EncodingCreated $event) {
        $encoding = $event->encoding;
        $form = $event->form;

        $this->encodingService->upsertEncodingChannel($encoding);

        $questions = $this->formService->getQuestions($form);
        $this->encodingService->addDefaultBranch($encoding, $questions);
    }

    /** @var EncodingService  */
    protected $encodingService;
    /** @var FormService  */
    protected $formService;

    public function __construct(EncodingService $encodingService, FormService $formService) {
        $this->encodingService = $encodingService;
        $this->formService = $formService;
    }
}
