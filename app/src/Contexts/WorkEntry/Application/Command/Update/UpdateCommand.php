<?php declare (strict_types=1);

namespace App\Contexts\WorkEntry\Application\Command\Update;

class UpdateCommand {
    private function __construct(
        private string $entry_id,
        private ?string $start_date = null,
        private ?string $end_date = null 
    ) {}

    public static function create(
        string $entry_id, 
        string $start_date = null,
        string $end_date = null
    ): static
    {
        return new self($entry_id, $start_date, $end_date);
    }
}
