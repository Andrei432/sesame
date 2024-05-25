<?php declare(strict_types=1);

namespace App\Contexts\UserManagement\Infrastructure;

use App\Contexts\UserManagement\Domain\UserRepositoryInterface;
use App\Contexts\UserManagement\Domain\User as DomainUser;

use App\Entity\User as DoctrineUser; 
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

# DDD Concept: This repository implementation knows only about the objects form the domain in its context. 
# Inputs and outputs are always Domain Entities. 

# In a more advanced implementation, the conversion between Doctrine Entities and Domain Entities
# should be done by introspection or some technique or framework. 
# In SQLAlchemy, you have the start_mappers function which is able to automatically generate the mapping. 

class UserRepositoryImpl extends ServiceEntityRepository implements UserRepositoryInterface {

    private EntityManager $entityManager;

    public function __construct(private ManagerRegistry $managerRegistry){
        parent::__construct($managerRegistry, DoctrineUser::class);
        
        $this->entityManager = $this->managerRegistry->getManagerForClass(DoctrineUser::class);

    }

    public function save(DomainUser $user): void {
        $doctrineUser = new DoctrineUser();
        $doctrineUser->setEmail($user->getEmail());
        $doctrineUser->setPassword($user->getPassword());
        $doctrineUser->setName($user->getName());
        $doctrineUser->setApiToken($user->getApiToken());
        $doctrineUser->setRole($user->getRole());

        # Time related stuff 
        $doctrineUser->setCreatedAt(new \DateTimeImmutable());
        $doctrineUser->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($doctrineUser);
        $this->entityManager->flush();
    }

    public function getToken(string $email): ?string
    {   
        $doctrine_user = $this->findOneBy(['email' => $email]);

        if ($doctrine_user === null) {
            return null;
        }
        return $doctrine_user->getApiToken();
    }

    public function userExistsByToken(string $token): bool
    {
        $doctrine_user = $this->findOneBy(['api_token' => $token]);

        if ($doctrine_user === null) {
            return false;
        }
        return true;
    }

    public function getUser(string $email, string $password): ?DomainUser
    {
        $hashed_password = hash('sha256', $password);

        $doctrine_user = $this->findOneBy(['email' => $email, 'password' => $hashed_password]);

        if ($doctrine_user === null) {
            return null;
        }

        return DomainUser::fromDoctrineUser($doctrine_user);
        
    }

}