<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use App\Enum\Post\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function createPostListQueryBuilder(User $user): Query
    {
        $sql = '
            SELECT p as post, r.type as reaction
            FROM App\Entity\Post p
            LEFT JOIN App\Entity\Reaction r WITH p.id = r.targetId AND r.targetType = :targetType AND r.author = :userId
            WHERE p.author IN (:followedUsers) OR p.type = :postType
            ORDER BY p.createdAt DESC
        ';

        $followedUsers = array_merge(
            [$user],
            $user->getFollows()->toArray()
        );

        return $this
            ->getEntityManager()
            ->createQuery($sql)
            ->setParameters([
                'targetType' => 'post',
                'postType' => Type::PUBLIC,
                'userId' => $user->getId(),
                'followedUsers' => $followedUsers,
            ]);
    }

    public function createPostListForUserQueryBuilder(?int $userId): Query
    {
        $sql = '
            SELECT p as post, r.type as reaction
            FROM App\Entity\Post p
            LEFT JOIN App\Entity\Reaction r WITH p.id = r.targetId AND r.targetType = :targetType AND r.author = :userId
            WHERE p.author = :userId
            ORDER BY p.createdAt DESC
        ';

        return $this
            ->getEntityManager()
            ->createQuery($sql)
            ->setParameters([
                'targetType' => 'post',
                'userId' => $userId,
            ]);
    }
}
