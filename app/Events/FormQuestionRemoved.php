<?php

namespace App\Events;

use App\Form;
use App\Models\Question;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FormQuestionRemoved {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(Form $form, Question $question) {
        $this->form = $form;
        $this->question = $question;
    }

    public $form;
    public $question;

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return new PrivateChannel('FormQuestionRemoved');
    }
}
