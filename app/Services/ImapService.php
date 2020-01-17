<?php

declare(strict_types=1);

namespace App\Services;

class ImapService
{
    private $connection;

    public function __construct()
    {
        $host = config('mail.extra.doctor.imap.host', '');
        $port = config('mail.extra.doctor.imap.port', '');
        $flags = config('mail.extra.doctor.imap.flags', '');
        $username = config('mail.extra.doctor.imap.username', '');
        $password = config('mail.extra.doctor.imap.password', '');

        $mailbox = '{' . $host . ':' . $port . $flags . '}INBOX';

        $this->connection = imap_open($mailbox, $username, $password, OP_READONLY);
    }

    public function __destruct()
    {
        imap_close($this->connection);
    }

    public function fetchImapMessage($message): ImapMessage
    {
        $message = new ImapMessage($this->connection, $message);
        $message->fetchData();

        return $message;
    }


    public function getUnseenMessages(): array
    {
        return imap_search($this->connection, 'UNSEEN') ?: [];
    }

    public function getMessageSender($message): string
    {
        $header = imap_headerinfo($this->connection, $message);
        return $header->from[0]->mailbox . '@' . $header->from[0]->host;
    }
}