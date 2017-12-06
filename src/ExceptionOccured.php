<?php

namespace Philipmorrisp\LaravelExceptionEmailNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ExceptionOccured extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, InteractsWithQueue;

    /**
     * The body of the message.
     *
     * @var string
     */
    public $content;

    /**
     * Create a new message instance.
     *
     * @param $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $to   = str_getcsv(config('email-exceptions.to'),',');
        $from = config('email-exceptions.from');
        $fromName = config('email-exceptions.from_name');
        $subject = config('email-exceptions.subject');

        if (config('email-exceptions.cc')) {
            $this->cc(explode(',', config('email-exceptions.cc')));
        }

        if (config('email-exceptions.bcc')) {
            $this->bcc(explode(',', config('email-exceptions.bcc')));
        }

        return $this->from($from, $fromName)
                    ->to($to)
                    ->subject($subject)
                    ->view('email-exceptions-view::exception')
                    ->with('content', $this->content);
    }
}
