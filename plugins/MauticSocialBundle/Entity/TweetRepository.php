<?php

namespace MauticPlugin\MauticSocialBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * @extends CommonRepository<Tweet>
 */
class TweetRepository extends CommonRepository
{
    /**
     * @param string $search
     * @param int    $limit
     * @param int    $start
     * @param bool   $viewOther
     *
     * @return array
     */
    public function getTweetList($search = '', $limit = 10, $start = 0, $viewOther = false, array $ignoreIds = [])
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('partial t.{id, text, name, language}');

        if (!empty($search)) {
            if (is_array($search)) {
                $search = array_map('intval', $search);
                $qb->andWhere($qb->expr()->in('t.id', ':search'))
                    ->setParameter('search', $search);
            } else {
                $qb->andWhere($qb->expr()->like('t.name', ':search'))
                    ->setParameter('search', "%{$search}%");
            }
        }

        if (!$viewOther) {
            $qb->andWhere($qb->expr()->eq('t.createdBy', ':id'))
                ->setParameter('id', $this->currentUser->getId());
        }

        if (!empty($ignoreIds)) {
            $qb->andWhere($qb->expr()->notIn('t.id', ':ignoreIds'))
                ->setParameter('ignoreIds', $ignoreIds);
        }

        $qb->orderBy('t.name');

        if (!empty($limit)) {
            $qb->setFirstResult($start)
                ->setMaxResults($limit);
        }

        return $qb->getQuery()->getArrayResult();
    }
}
