<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Reaction;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reaction>
 *
 * @method Reaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reaction[]    findAll()
 * @method Reaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reaction::class);
    }

    public function save(Reaction $reaction, bool $flush = true): void
    {
        $this->getEntityManager()->persist($reaction);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reaction $reaction): void
    {
        $this->getEntityManager()->remove($reaction);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByTargetAndAuthor(string $targetType, int $targetId, User $author): ?Reaction
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.targetType = :targetType')
            ->andWhere('r.targetId = :targetId')
            ->andWhere('r.author = :author')
            ->setParameter('targetType', $targetType)
            ->setParameter('targetId', $targetId)
            ->setParameter('author', $author)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findReactionForTarget(string $targetType, int $targetId): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.type')
            ->andWhere('r.targetType = :targetType')
            ->andWhere('r.targetId = :targetId')
            ->setParameter('targetType', $targetType)
            ->setParameter('targetId', $targetId)
            ->getQuery()
            ->getResult();
    }
}
