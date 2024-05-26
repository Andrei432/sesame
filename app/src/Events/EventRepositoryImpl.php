<?php declare(strict_types=1);

namespace App\Events;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Generator;


use App\Entity\Event as DoctrineEvent;

class EventRepositoryImpl extends ServiceEntityRepository implements EventRepositoryInterface {

    private EntityManager $entityManager;

    public function __construct(private ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, DoctrineEvent::class);
        $this->entityManager = $this->managerRegistry->getManagerForClass(DoctrineEvent::class); 
        
    }

    public function save(Event $event): void
    {
        $doctrineEvent = new DoctrineEvent();
        $doctrineEvent->setUserId($event->getUserId());
        $doctrineEvent->setWorkEntryId($event->getWorkEntryId());
        $doctrineEvent->setUserId($event->getUserId());
        $doctrineEvent->setAction($event->getAction());

        $doctrineEvent->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($doctrineEvent);
        $this->entityManager->flush();
    }

    public function all(): Generator 
    {
        foreach ($this->findAll() as $event) 
            yield $event;
    }
}