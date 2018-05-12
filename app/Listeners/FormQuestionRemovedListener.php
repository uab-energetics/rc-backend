<?php

namespace App\Listeners;

use App\Events\FormQuestionRemoved;
use App\Services\Encodings\EncodingService;
use App\Services\Questions\QuestionService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FormQuestionRemovedListener {

    public function handle(FormQuestionRemoved $event) {
        foreach ($event->form->encodings()->get() as $encoding) {
            $this->encodingService->removeQuestion($encoding, $event->question);
        }
        $this->questionService->deleteQuestionIfDangling($event->question);
    }

    /** @var QuestionService  */
    protected $questionService;

    public function __construct(EncodingService $encodingService, QuestionService $questionService) {
        $this->encodingService = $encodingService;
        $this->questionService = $questionService;
    }
}
