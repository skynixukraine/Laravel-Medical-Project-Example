<?php

declare(strict_types=1);

namespace App\Services;

class MailService
{
    public static function useMailConfig(string $name = 'system'): void
    {
        if (config('mail.current') === $name) {
            return;
        }

        $configs =  config('mail.extra.' . $name, []);

        config([
            'mail.current' => $name,
            'mail.driver' => $configs['driver'] ?? null,
            'mail.host' => $configs['host'] ?? null,
            'mail.port' => $configs['port'] ?? null,
            'mail.username' => $configs['username'] ?? null,
            'mail.password' => $configs['password'] ?? null,
            'mail.encryption' => $configs['encryption'] ?? null,
        ]);
    }
}