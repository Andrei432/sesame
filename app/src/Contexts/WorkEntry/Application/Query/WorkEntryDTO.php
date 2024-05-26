<?php declare(strict_types=1);

namespace App\Contexts\WorkEntry\Application\Query;

use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

class WorkEntryDTO {

    private function __construct(
        private Uuid $id,
        private string $start_date, 
        private ?string $end_date
    )
    {}

    public static function create(
        Uuid $id,
        string $start_date, 
        ?string $end_date
    ): self
    {
        return new self(
            id: $id, 
            start_date: $start_date, 
            end_date: $end_date
        );
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getStartDate(): string
    {
        return $this->start_date;
    }

    public function getEndDate(): ?string
    {
        return $this->end_date;
    }
    
}