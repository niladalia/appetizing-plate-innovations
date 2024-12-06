<?php

namespace App\Shared\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use App\Shared\Utils\ClassUtils;
use App\Shared\ValueObject\NilUuid;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Selectable;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\AST\Functions\AbsFunction;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use PDO;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;

class BaseEntityRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em, ClassMetadata $classMetadata)
    {
        parent::__construct($em, $classMetadata);
    }

    /**
     * Remove an object by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     *
     * @return static Self reference.
     * @psalm-return static
     */
    public function remove($id, ?int $version = null): static
    {
        $entity = $this->find($id, $version !== null ? LockMode::OPTIMISTIC : null, $version);
        if (!$entity) {
            throw new EntityNotFoundException("Entity {$this->getEntityName()}@{$id} not found");
        }
        $this->getEntityManager()->remove($entity);
        return $this;
    }

    /**
     * Remove objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     *
     * @param array<string, mixed>       $criteria
     * @param array<string, string>|null $orderBy
     * @psalm-param array<string, 'asc'|'desc'|'ASC'|'DESC'>|null $orderBy
     *
     * @return static Self reference.
     * @psalm-return static
     *
     * @throws UnexpectedValueException
     */
    public function removeBy(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): static
    {
        $entities = $this->findBy($criteria, $orderBy, $limit, $offset);
        if (!$entities) {
            throw new EntityNotFoundException("Entities not found");
        }
        $em = $this->getEntityManager();
        foreach ($entities as $entity) {
            $em->remove($entity);
        }
        return $this;
    }

    /**
     * Remove a single object by a set of criteria.
     *
     * @param array<string, mixed> $criteria The criteria.
     *
     * @return Self reference.
     * @psalm-return static
     */
    public function removeOneBy(array $criteria, ?array $orderBy = null): static
    {
        $entity = $this->findOneBy($criteria, $orderBy);
        if (!$entity) {
            throw new EntityNotFoundException("Entity not found");
        }
        $this->getEntityManager()->remove($entity);
        return $this;
    }

    public function flush(): static
    {
        $this->getEntityManager()->flush();
        return $this;
    }

    public function persist(object $entity): static
    {
        $this->getEntityManager()->persist($entity);
        return $this;
    }

    public function find(mixed $id, LockMode|int|null $lockMode = null, ?int $lockVersion = null): ?object
    {
        $result = parent::find($id, $lockMode, $lockVersion);
        if ($result === null) {
            throw new EntityNotFoundException("Entity {$this->getClassName()}@{$id} not found");
        }
        return $result;
    }

    public function persistMulti(...$objects): static
    {
        foreach ($objects as $object) {
            $this->persist($object);
        }
        return $this;
    }
}
