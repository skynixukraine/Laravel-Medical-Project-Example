<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Enquire;
use App\Models\EnquireMessage;
use App\Services\StorageService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Webklex\IMAP\Attachment;
use Webklex\IMAP\Facades\Client;
use Webklex\IMAP\Message;

class ProcessPatientsMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var StorageService
     */
    private $storage;

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

    public function getStorageService(): StorageService
    {
        return $this->storage ?? $this->storage = new StorageService();
    }

    public function handle(): void
    {
        $messages = $this->client->getFolder('INBOX')->query()->unseen()->get();

        foreach ($messages as $message) {
            $enquire = Enquire::query()
                ->whereIn('email', array_map(function ($sender) { return $sender->mail; }, $message->getSender()))
                ->where('status', '<>', Enquire::STATUS_ARCHIVED)
                ->orderByDesc('created_at')
                ->first();

            if (!$enquire) { continue; }

            /**  @var $message Message */
            $enquireMessage = $enquire->messages()->create([
                'sender' => EnquireMessage::SENDER_PATIENT,
                'content' => $message->hasHTMLBody()
                    ? $message->mask()->getHTMLBodyWithUrlImages()
                    : $message->getTextBody(),
            ]);

            $message->getAttachments()->each(function (Attachment $attachment) use ($enquireMessage) {
                if ($attachment->type === 'image' && $attachment->id && $attachment->getImgSrc()) {
                    $enquireMessage->attachments()->create([
                        'path' => $this->getStorageService()->saveMessageEnquiryAttachment(
                            pathinfo($attachment->getName(), PATHINFO_FILENAME),
                            pathinfo($attachment->getName(), PATHINFO_EXTENSION),
                            $attachment->getContent())
                    ]);
                }
            });

            $message->delete();
        }
    }
}