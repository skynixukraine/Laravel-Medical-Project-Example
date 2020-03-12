<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Enquire;

class ConclusionUpdated
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