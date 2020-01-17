<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Enquire;
use App\Services\ImapService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessPatientsMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public function handle(): void
    {
        $imap = new ImapService();

        foreach ($imap->getUnseenMessages() as $message) {
            $enquire = Enquire::query()
                ->where('email', $imap->getMessageSender($message))
                ->where('status', '<>', Enquire::STATUS_ARCHIVED)
                ->orderByDesc('created_at')
                ->first();

            if (!$enquire) {
                continue;
            }

            $imapMessage = $imap->fetchImapMessage($message);
        }
    }
}