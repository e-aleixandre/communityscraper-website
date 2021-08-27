<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportCompleted extends Mailable
{
    use Queueable, SerializesModels;

    protected String $filename;
    protected String $contents;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($filename, $contents)
    {
        $this->filename = $filename;
        $this->contents = $contents;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.reports.completed')->attachData($this->contents, $this->filename, [
            'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }
}
