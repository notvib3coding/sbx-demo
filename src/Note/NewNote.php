<?php

namespace App\Note;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class NewNote
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 500)]
        public string $text = '',
    ) {
    }
}
