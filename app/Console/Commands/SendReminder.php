<?php

namespace App\Console\Commands;

use App\Notifications\ReminderNotification;
use App\Models\Submission;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class SendReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:send';

    /**
     * The console command description.
     *
     * @var string
     */
     protected $description = 'Sends reminder to doctors for permanently assigned submissions, when half of the remaining time is over.';

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


        // fixme: this requires that mysql uses Berlin Timezone too (like php is configured in config/app.php) !
        $submissions = Submission
            ::whereRaw('TIMESTAMPDIFF(HOUR, NOW(), due_at) <= ceil(responsetime/2)')
            ->where('status', 'permanently_assigned')
            ->whereNull('reminder_sent_at')
            ->get();

        $remindersSent = 0;

        foreach ($submissions AS $submission) {
            $assignedTo = $submission->assignedTo;
            if ($assignedTo->email) {
                try {
                    Notification::route('mail', $assignedTo->email)
                        ->notify(new ReminderNotification($submission));
                    $submission->reminder_sent_at = Carbon::now();
                    $submission->save();
                    $remindersSent++;
                }
                catch (\Exception $e) {
                    report($e);
                }
            }
        }

        $this->info($remindersSent . " reminders sent");
        return;
    }
}
