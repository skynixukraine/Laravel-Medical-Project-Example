<?php

namespace App\Mail;

use App\Models\Partner;
use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;

class NewSubmission extends Mailable
{
    use Queueable, SerializesModels;

    public $submission,
        $partner;

    /**
     * Create a new message instance.
     *
     * @param Submission $submission
     */
    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
        $this->partner = Partner::find($submission->partner_id);
        App::setLocale($this->partner->language);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->markdown('mail.new-submission')
            ->subject(__('case-submit.new_case_available'))
            ->from($this->partner->mail_from_address, $this->partner->mail_from_name)
            ->replyTo($this->partner->mail_from_address);
    }
}
