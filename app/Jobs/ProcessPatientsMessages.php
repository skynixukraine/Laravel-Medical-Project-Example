<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Enquire;
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
        $config = config('mail.extra.doctor.imap');

        $connection = imap_open('{' . $config['host'] . ':' . $config['port'] . $config['flags'] . '}INBOX',
            $config['username'], $config['password'], OP_READONLY);

        $unseenMessages = imap_search($connection, 'UNSEEN');


        foreach ($unseenMessages as $message) {
            $header = imap_headerinfo($connection, $message);
            $sender = $header->from[0]->mailbox . '@' . $header->from[0]->host;

            $enquire = Enquire::query()
                ->where('email', $sender)
                ->where('status', '<>', Enquire::STATUS_ARCHIVED)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$enquire) {
                continue;
            }

            $attachments = $this->extractAttachments($connection, $message);
        }

        imap_close($connection);
    }

    private function extractAttachments($connection, $messageNumber): array
    {
        $attachments = [];
        $structure = imap_fetchstructure($connection, $messageNumber);

        if (isset($structure->parts) && count($structure->parts)) {
            for ($i = 0, $count = count($structure->parts); $i < $count; $i++) {
                if ($structure->parts[$i]->type !== 5) {
                    continue;
                }

                if ($structure->parts[$i]->ifdparameters) {
                    foreach ($structure->parts[$i]->dparameters as $object) {
                        if (strtolower($object->attribute) === 'filename') {
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }

                $attachments[$i]['value'] = imap_fetchbody($connection, $messageNumber, $i + 1);

                if ($structure->parts[$i]->encoding === 3) {
                    $attachments[$i]['value'] = base64_decode($attachments[$i]['value']);
                } elseif ($structure->parts[$i]->encoding === 4) {
                    $attachments[$i]['value'] = quoted_printable_decode($attachments[$i]['value']);
                }
            }
        }

        return $attachments;
    }
}