<?php

namespace App\Note;

final readonly class Note
{
    public function __construct(
        public int $id,
        public string $text,
        public string $createdAt,
    ) {
    }
}
