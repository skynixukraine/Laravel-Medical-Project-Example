<?php

namespace App\Notifications;

use App\Partner;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class MyNotification extends Notification
{
    use Queueable;

    public function __construct($partner = null)
    {
        if (!$partner) $partner = Partner::find(1);
        // needed to set an extra css class for the email template
        Config::set('app.partner',      $partner->partner_id);
        // the headline of our email template shows app.name. So we need set differently for OHN/ITA
        Config::set('app.name',         Config::get('app.' . $partner->partner_id . '.name'));
        Config::set('app.MIX_WEB_URL',  Config::get('app.' . $partner->partner_id . '.MIX_WEB_URL'));

        App::setLocale($partner->language);
    }

}
