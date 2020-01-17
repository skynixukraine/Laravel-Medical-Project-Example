<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Str;

class ImapMessage
{
    private $mimeTypes = [
        TYPEMULTIPART => 'MULTIPART',
        TYPEIMAGE => 'IMAGE',
        TYPETEXT => 'TEXT',
    ];

    private $connection;

    private $messageNumber;

    private $plain;

    private $html;

    private $images;

    public function __construct($connection, $messageNumber)
    {
        $this->connection = $connection;
        $this->messageNumber = $messageNumber;
    }

    public function fetchData(): void
    {
        $structure = imap_fetchstructure($this->connection, $this->messageNumber);

        $this->fetchBodyPart($structure);
    }

    private function fetchBodyPart($bodyPart, $level = -1, $sublevel = 0): void
    {
        $processMethod = 'fetchType' . Str::studly($this->mimeTypes[$bodyPart->type] ?? 'Unknown');

        if (method_exists($this, $processMethod)) {
            $this->$processMethod($bodyPart, $level, $sublevel);
        }
    }

    private function fetchTypeMultipart($bodyPart, $level, $sublevel): void
    {
        foreach ($bodyPart->parts as $index => $part) {
            $this->fetchBodyPart($part, $level + 1, $index + 1);
        }
    }

    private function fetchTypeText($bodyPart, $level, $sublevel): void
    {
        if ($bodyPart->ifsubtype) {
            $processMethod = 'fetchSubtype' . Str::studly($bodyPart->subtype);

            if (method_exists($this, $processMethod)) {
                $this->$processMethod($bodyPart->encoding, $level . '.' . $sublevel);
            }
        }
    }

    private function fetchTypeImage($bodyPart, $level): void
    {
        $this->images[] = $this->decode($bodyPart->encoding, imap_fetchbody($this->connection, $this->messageNumber, (string) $level));
    }

    private function fetchSubtypePlain($encoding, $section): void
    {
        $this->plain = $this->decode($encoding, imap_fetchbody($this->connection, $this->messageNumber, $section));
    }

    private function fetchSubtypeHtml($encoding, $section): void
    {
        $this->html = $this->decode($encoding, imap_fetchbody($this->connection, $this->messageNumber, $section));
    }

    private function decode($encoding, $content)
    {
        if ($encoding === 3) {
            return base64_decode($content);
        }

        if ($encoding === 4) {
            return quoted_printable_decode($content);
        }

        return $content;
    }
}