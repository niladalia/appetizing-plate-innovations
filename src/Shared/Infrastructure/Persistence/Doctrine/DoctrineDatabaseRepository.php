<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\Repository\DatabaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class DoctrineDatabaseRepository extends ServiceEntityRepository implements DatabaseRepository
{
    protected function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    public function save(AggregateRoot $object): void
    {
        $this->persist($object);
        $this->apply();
    }

    public function persist(AggregateRoot $object): void
    {
        $this->getEntityManager()->persist($object);
    }

    public function apply(): void
    {
        $em = $this->getEntityManager();
        $em->flush();
        $em->clear();
    }

}
