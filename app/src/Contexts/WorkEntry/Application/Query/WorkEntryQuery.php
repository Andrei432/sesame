<?php declare (strict_types=1);

namespace App\Contexts\WorkEntry\Application\Query;

use App\Contexts\WorkEntry\Domain\WorkEntryRepositoryInterface;

use Generator;
use Symfony\Component\Uid\Uuid;

class WorkEntryQuery
{
    public function __construct(private WorkEntryRepositoryInterface $repository)
    {}

    public function getWorkEntries(Uuid $user_id): Generator
    {
        foreach ($this->repository->findByUserId($user_id) as $work_entry) {
            yield WorkEntryDTO::create(
                id: $work_entry->getId(),
                start_date: $work_entry->getStartDate(),
                end_date: $work_entry->getEndDate() ? $work_entry->getEndDate() : ''
            ); 
        }
    }

    public function getWorkEntryById(string $entry_id): ?WorkEntryDTO {
        $domainWorkEntry = $this->repository->findById(Uuid::fromString($entry_id));
        return WorkEntryDTO::create(
            id: $domainWorkEntry->getId(),
            start_date: $domainWorkEntry->getStartDate(),
            end_date: $domainWorkEntry->getEndDate()
        ); 
    }
}