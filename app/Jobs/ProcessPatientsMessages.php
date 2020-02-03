<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Facades\Storage;
use App\Models\Enquire;
use App\Models\EnquireMessage;
use App\Notifications\EnquireMessageCanNotBeCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Webklex\IMAP\Attachment;
use Webklex\IMAP\Facades\Client;
use Webklex\IMAP\Message;
use Webklex\IMAP\Support\Masks\MessageMask;

class ProcessPatientsMessages implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var \Webklex\IMAP\Client
     */
    private $client;

    private $imapAccount = 'default';

    public $tries = 1;

    public function __construct()
    {
        $this->initClient();
    }

    public function __wakeup()
    {
        $this->initClient();
    }

    private function initClient(): void
    {
        $this->client = Client::account($this->imapAccount);
        $this->client->connect();
    }

    public function handle(): void
    {
        $messages = $this->client->getFolder('INBOX')->query()->unseen()->get();

        foreach ($messages as $message) {
            $enquire = Enquire::query()
                ->whereIn('email', array_map(function ($sender) { return $sender->mail; }, $message->getSender()))
                ->orderByDesc('created_at')
                ->first();

            if (!$enquire) { continue; }

            if ($enquire->status === Enquire::STATUS_ARCHIVED) {
                $enquire->notify(new EnquireMessageCanNotBeCreated());
                $message->delete();
                continue;
            }

            /**  @var $message Message */
            $message->setMask(MessageMask::class);
            $enquireMessage = $enquire->messages()->create([
                'sender' => EnquireMessage::SENDER_PATIENT,
                'content' => $message->hasHTMLBody() ? $message->mask()->getHTMLBody() : $message->getTextBody(),
            ]);

            $message->getAttachments()->each(function (Attachment $attachment) use ($enquireMessage) {
                if ($attachment->type === 'image' && $attachment->id && $attachment->getImgSrc()) {
                    $enquireMessage->attachments()->create([
                        'path' => Storage::saveMessageEnquiryAttachment(
                            $attachment->getContent(),
                            pathinfo($attachment->getName(), PATHINFO_FILENAME))
                    ]);
                }
            });

            $message->delete();
        }
    }
}