<?php declare(strict_types=1);

namespace App\Contexts\WorkEntry\Domain;

use Symfony\Component\Uid\Uuid;

# This class represents the root aggregate: 
class WorkEntry
{

    private function __construct(
        private ?Uuid $id,
        private UUid $user_id,
        private ?string $start_date,
        private ?string $end_date
    )
    {}

    public static function create(
        Uuid $user_id,
        ?string $start_date,
        ?string $end_date, 
        ?Uuid $id = null, 

    ): WorkEntry
    {
        return new WorkEntry(
            id: $id,
            user_id: $user_id,
            start_date: $start_date,
            end_date: $end_date
        );
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUserId(): Uuid
    {
        return $this->user_id;
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