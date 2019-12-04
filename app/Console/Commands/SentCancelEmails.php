<?php

namespace App\Console\Commands;

use App\Notifications\CancelNotification;
use App\Notifications\ReminderNotification;
use App\Submission;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SentCancelEmails extends Command
{
    protected $minutesAgoMin = 30;
    protected $minutesAgoMax = 60; // This is only needed to avoid sending hundreds of email after deployment

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancelEmails:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends emails to submissions which were created 30min ago and not finished.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info("Handling SentCancelEmails");

        $until = Carbon::now()->subMinutes($this->minutesAgoMin);
        $from  = Carbon::now()->subMinutes($this->minutesAgoMax);

        $submissions = Submission
            ::where('created_at' , '<=', $until)
            ->where('created_at' , '>', $from)
            ->where('status', 'setup')
            ->whereNull('cancel_email_sent_at')
            ->get();
        $emailsSent = 0;

        foreach ($submissions AS $submission) {
            if ($submission->email) {
                try {
                    Notification::route('mail', $submission->email)
                        ->notify(new CancelNotification($submission));
                    $submission->cancel_email_sent_at = Carbon::now();
                    $submission->save();
                    $emailsSent++;
                }
                catch (\Exception $e) {
                    report($e);
                }
            }
        }

        $this->info($emailsSent . " cancelEmails sent");
        return;
    }
}
