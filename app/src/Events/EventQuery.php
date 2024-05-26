<?php declare(strict_types=1);

namespace App\Events;

use Generator;

class EventQuery
{

    public function __construct(private EventRepositoryInterface $repository) {}

    public function all(): Generator {
        yield from $this->repository->all();
    }

}
