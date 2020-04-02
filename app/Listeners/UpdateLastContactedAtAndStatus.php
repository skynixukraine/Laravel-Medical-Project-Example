<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\EnquireMessageCreated;
use App\Models\Enquire;
use App\Models\EnquireMessage;

class UpdateLastContactedAtAndStatus
{
    public function handle(EnquireMessageCreated $event)
    {
        ($message = $event->getMessage())->enquire->update([
            'last_contacted_at' => $message->created_at,
            'status' => $message->sender === EnquireMessage::SENDER_PATIENT
                ? Enquire::STATUS_WAIT_DOCTOR_RESPONSE
                : Enquire::STATUS_WAIT_PATIENT_RESPONSE
        ]);
    }
}