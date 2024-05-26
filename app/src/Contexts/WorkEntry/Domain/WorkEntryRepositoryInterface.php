<?php declare(strict_types=1);

namespace App\Contexts\WorkEntry\Domain;

use Generator;
use Symfony\Component\Uid\Uuid;

interface WorkEntryRepositoryInterface
{
   
    public function save(WorkEntry $workEntry): void;

    public function delete(Uuid $entry_id): void;

    public function findByUserId(Uuid $user_id): Generator;

    public function update(Uuid $id, ?string $start_date=null, ?string $end_date=null): void;

}
