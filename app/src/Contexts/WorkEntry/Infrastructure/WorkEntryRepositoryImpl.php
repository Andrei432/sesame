<?php declare(strict_types=1);

namespace App\Contexts\WorkEntry\Infrastructure;

use App\Contexts\WorkEntry\Domain\WorkEntryRepositoryInterface;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\WorkEntry as DoctrineWorkEntry;

use App\Contexts\WorkEntry\Domain\WorkEntry as DomainWorkEntry;
use Generator;
use Symfony\Component\Uid\Uuid;

class WorkEntryRepositoryImpl extends ServiceEntityRepository implements WorkEntryRepositoryInterface {

    private EntityManager $entityManager; 

    public function __construct(private ManagerRegistry $managerRegistry) {
        parent::__construct($managerRegistry, DoctrineWorkEntry::class);
        $this->entityManager = $this->managerRegistry->getManagerForClass(DoctrineWorkEntry::class);        
    }

    public function save(DomainWorkEntry $workEntry): void {
        
        $doctrineWorkEntry = new DoctrineWorkEntry();
        $doctrineWorkEntry->setUserId($workEntry->getUserId());
        $doctrineWorkEntry->setStartDate($workEntry->getStartDate());
        $doctrineWorkEntry->setEndDate($workEntry->getEndDate());
        $doctrineWorkEntry->setCreatedAt( new \DateTimeImmutable() );
        $doctrineWorkEntry->setUpdatedAt( new \DateTimeImmutable() );
        
        $this->entityManager->persist($doctrineWorkEntry);
        $this->entityManager->flush();
    }

    public function delete(Uuid $id): void {
        $doctrineWorkEntry = $this->entityManager->getRepository(DoctrineWorkEntry::class)->find($id);
        $this->entityManager->remove($doctrineWorkEntry);

    }

    public function findByUserId(Uuid $user_id):  Generator {
        # Use doctrine to query rows with the given user id 
        foreach ($this->entityManager->getRepository(DoctrineWorkEntry::class)->findBy(['user_id' => $user_id]) as $row) {
            yield DomainWorkEntry::create(
                id: $row->getId(),
                user_id: $row->getUserId(),
                start_date: $row->getStartDate(),
                end_date: $row->getEndDate()
            );
        }
    }

    public function update(Uuid $id, ?string $start_date=null, ?string $end_date=null): void {
        $doctrineWorkEntry = $this->entityManager->getRepository(DoctrineWorkEntry::class)->find($id);
        $doctrineWorkEntry->setStartDate($start_date);
        $doctrineWorkEntry->setEndDate($end_date);
        $doctrineWorkEntry->setUpdatedAt( new \DateTimeImmutable() );
        $this->entityManager->flush();
    }

    public function findById(Uuid $id): DomainWorkEntry {
        $doctrineWorkEntry = $this->entityManager->getRepository(DoctrineWorkEntry::class)->find($id);
        return DomainWorkEntry::create(
            id: $doctrineWorkEntry->getId(),
            user_id: $doctrineWorkEntry->getUserId(),
            start_date: $doctrineWorkEntry->getStartDate(),
            end_date: $doctrineWorkEntry->getEndDate()
        );
    }
}


