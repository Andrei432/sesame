<?php declare(strict_types=1);

namespace App\Contexts\WorkEntry\Application\Command\Register;

use Symfony\Component\Uid\Uuid;

class RegisterCommand {
    private function __construct(
        private Uuid $user_id,
        private String $start_date,
        private ?String $end_date,

    ) {
    }

    public static function create(
        Uuid $user_id,
        String $start_date,
        ?String $end_date
    ): self
    {
        return new self($user_id, $start_date, $end_date);
    }

    public function getUserId(): Uuid
    {
        return $this->user_id;
    }

    public function getStartDate(): String
    {
        return $this->start_date;
    }

    public function getEndDate(): ?String
    {
        return $this->end_date;
    }
}

