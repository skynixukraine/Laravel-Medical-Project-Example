<?php

declare(strict_types=1);

namespace App\Events;

class EnquireVerifiedEmail
{
    private $enquire;

    public function __construct(Enquire $enquire)
    {
        $this->enquire = $enquire;
    }

    public function getEnquire(): Enquire
    {
        return $this->enquire;
    }
}