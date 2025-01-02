<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getPostComments(int $postId, $userId): array
    {
        $sql = '
            SELECT c as comment, r.type as reaction
            FROM App\Entity\Comment c
            LEFT JOIN App\Entity\Reaction r WITH c.id = r.targetId AND r.targetType = :targetType AND r.author = :userId
            WHERE c.post = :postId
            ORDER BY c.createdAt DESC
        ';

        return $this
            ->getEntityManager()
            ->createQuery($sql)
            ->setParameters([
                'targetType' => 'comment',
                'postId' => $postId,
                'userId' => $userId,
            ])
            ->getResult();
    }
}
