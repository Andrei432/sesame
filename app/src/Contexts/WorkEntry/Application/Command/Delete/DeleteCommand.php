<?php declare(strict_types=1);

namespace App\Contexts\WorkEntry\Application\Command\Delete;

use Symfony\Component\Uid\Uuid;

class DeleteCommand {
    private function __construct(private Uuid $entry_id) {
    }

    public static function create(string $entry_id): static
    {
        return new self(Uuid::fromString($entry_id));
    }

    public function getEntryId(): Uuid
    {
        return $this->entry_id;
    }
}
