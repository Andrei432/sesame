<?php declare(strict_types=1);

namespace App\Events;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('event.bus')]
class EventHandler
{   
    # We skip the service layer here. 
    # We don't need that layer of indirection because we dont want do nothing more than 
    # saving/getting the event to the repository.
    public function __construct(private EventRepositoryInterface $eventRepository){
    }

    public function __invoke(Event $event): void {
        $this->eventRepository->save($event);
    }
}