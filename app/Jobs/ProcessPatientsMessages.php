<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Enquire;
use App\Models\EnquireMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessPatientsMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    public function handle()
    {
        $config = config('mail.doctor');

        $conn = imap_open('{' . $config['host'] . ':' . $config['port'] . $config['flags'] . '}INBOX', $config['username'], $config['password'], OP_READONLY);

        $unseenMessages = imap_search($conn,'UNSEEN');

        foreach ($unseenMessages as $message) {
            $header = imap_headerinfo($conn, $message);
            $sender = $header->from[0]->mailbox . '@' . $header->from[0]->host;

            $enquire = Enquire::query()
                ->where('email', $sender)
                ->where('status', '<>', Enquire::STATUS_ARCHIVED)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$enquire) {
                continue;
            }

            $content = quoted_printable_decode(imap_fetchbody($conn, $message, 1));

            EnquireMessage::create([
                'sender' => EnquireMessage::SENDER_PATIENT,
                'content' => $content,
                'enquire_id' => $enquire->id
            ]);
        }
    }
}