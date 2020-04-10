<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Contact;
use Illuminate\Queue\SerializesModels;

class ContactCreated
{
    use SerializesModels;

    private $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function getContact(): Contact
    {
        return $this->contact;
    }
}
