<?php

namespace KingFoo\BookmarkerBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function findCloud($username = null)
    {
        $builder = $this
            ->createQueryBuilder('t')
            ->select('t, count(t.id) as saveCount')
            ->innerJoin('t.bookmarks', 'b')
            ->groupBy('t.id')
            ->orderBy('t.label');

        if ($username) {
            $builder
                ->innerJoin('b.user', 'u')
                ->where('u.username = :username')
                ->setParameter('username', $username);
        }

        return $builder->getQuery()->getResult();
    }
}
