<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolutionCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $solution;

    /**
     * Create a new message instance.
     *
     * @param $solution
     */
    public function __construct($solution)
    {
        $this->solution = $solution;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.admin.solution_created');
    }
}
