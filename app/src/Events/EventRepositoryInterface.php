<?php declare(strict_types=1);

namespace App\Events;

use Generator;

interface EventRepositoryInterface {

    public function save(Event $event): void;

    public function all(): Generator; 
}