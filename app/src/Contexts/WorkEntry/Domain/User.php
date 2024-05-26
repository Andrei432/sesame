<?php declare(strict_types=1);

namespace App\Contexts\WorkEntry\Domain;

use Symfony\Component\Uid\Uuid;

class User {
    private function __construct(private Uuid $id) {}
    public static function create(Uuid $id): static {
        return new self($id);
    }
}