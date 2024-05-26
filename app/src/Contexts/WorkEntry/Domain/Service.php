<?php declare(strict_types=1);

namespace App\Contexts\WorkEntry\Domain;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;



use App\Events\ConcreteEvents\WorkEntry\WorkEntryCreatedEvent; 
use App\Events\ConcreteEvents\WorkEntry\WorkEntryUpdatedEvent; 
use App\Events\ConcreteEvents\WorkEntry\WorkEntryDeletedEvent; 

class Service {

    public function __construct(
        private WorkEntryRepositoryInterface $repository, 
        private MessageBusInterface $eventBus
    ){}

    public function save(WorkEntry $workEntry): void {
        $this->repository->save($workEntry);
        $this->eventBus->dispatch(WorkEntryCreatedEvent::create());
    }

    public function update(Uuid $id, ?string $start_date, ?string $end_date): void {
        $this->repository->update($id, $start_date, $end_date);
        $this->eventBus->dispatch(WorkEntryUpdatedEvent::create(work_entry_id: $id));
    }

    public function delete(Uuid $entry_id): void {
        $this->repository->delete($entry_id);
        $this->eventBus->dispatch(WorkEntryDeletedEvent::create(work_entry_id: $entry_id));
    }

}