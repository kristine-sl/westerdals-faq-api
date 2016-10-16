<?php

namespace App\Events;

use App\Question;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class QuestionPosted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * The question model.
     *
     * @var \App\Question
     */
    public $question;

    /**
     * Create a new event instance.
     *
     * @param  Question $question
     * @return void
     */
    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('westerdals-faq');
    }
}
