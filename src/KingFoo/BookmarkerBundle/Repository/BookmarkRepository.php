<?php

namespace KingFoo\BookmarkerBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BookmarkRepository extends EntityRepository
{
    public function findFeed($limit = 20, $offset = 0)
    {
        $query = $this
            ->createQueryBuilder('b')
            ->select('b, u, t')
            ->innerJoin('b.user', 'u')
            ->leftJoin('b.tags', 't')
            ->orderBy('b.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);

        return $paginator;
    }

    public function findForUsername($username, $limit = 20, $offset = 0)
    {
        $query = $this
            ->createQueryBuilder('b')
            ->select('b, t')
            ->innerJoin('b.user', 'u')
            ->leftJoin('b.tags', 't')
            ->andWhere('u.username = :username')
            ->orderBy('b.createdAt', 'DESC')
            ->setParameter('username', $username)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);

        return $paginator;
    }

    public function findForTag($tag, $limit = 20, $offset = 0)
    {
        $query = $this
            ->createQueryBuilder('b')
            ->select('b, t')
            ->innerJoin('b.user', 'u')
            ->leftJoin('b.tags', 't')
            ->andWhere('t.label = :tag')
            ->orderBy('b.createdAt', 'DESC')
            ->setParameter('tag', $tag)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);

        return $paginator;
    }

    public function findPopular($limit = 20)
    {
        $query = $this
            ->createQueryBuilder('b')
            ->select('b, count(b) as saveCount')
            ->where('b.createdAt > :createdAt')
            ->groupBy('b.url')
            ->orderBy('saveCount', 'DESC')
            ->setMaxResults($limit)
            ->setParameter('createdAt', new \DateTime('-1 month'))
            ->getQuery();

        return $query->getResult();
    }
}
