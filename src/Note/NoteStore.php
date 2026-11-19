<?php

namespace App\Note;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Keeps notes in one JSON file under var/. Enough for a demo; a real
 * database comes in a later video.
 */
final class NoteStore
{
    private string $file;

    public function __construct(#[Autowire('%kernel.project_dir%/var/notes.json')] string $file)
    {
        $this->file = $file;
    }

    /** @return list<Note> */
    public function all(): array
    {
        if (!is_file($this->file)) {
            return [];
        }
        $rows = json_decode((string) file_get_contents($this->file), true, flags: JSON_THROW_ON_ERROR);

        return array_map(static fn (array $row) => new Note($row['id'], $row['text'], $row['createdAt']), $rows);
    }

    public function add(string $text): Note
    {
        $notes = $this->all();
        $id = 1 + max([0, ...array_map(static fn (Note $note) => $note->id, $notes)]);
        $note = new Note($id, $text, (new \DateTimeImmutable())->format(\DATE_ATOM));
        $notes[] = $note;
        $this->save($notes);

        return $note;
    }

    /** @param list<Note> $notes */
    private function save(array $notes): void
    {
        if (!is_dir(\dirname($this->file))) {
            mkdir(\dirname($this->file), recursive: true);
        }
        file_put_contents($this->file, json_encode($notes, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR), LOCK_EX);
    }
}
