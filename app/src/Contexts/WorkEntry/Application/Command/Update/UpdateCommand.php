<?php declare (strict_types=1);

namespace App\Contexts\WorkEntry\Application\Command\Update;

use Symfony\Component\Uid\Uuid;

class UpdateCommand {
    private function __construct(
        private Uuid $entry_id,
        private ?string $start_date = null,
        private ?string $end_date = null 
    ) {}

    public static function create(
        string $entry_id, 
        string $start_date = null,
        string $end_date = null
    ): static
    {
        return new self(Uuid::fromString($entry_id), $start_date, $end_date);
    }

    public function getEntryId(): Uuid
    {
        return $this->entry_id;
    }

    public function getStartDate(): ?string
    {
        return $this->start_date;
    }

    public function getEndDate(): ?string
    {
        return $this->end_date;
    }

    
}
