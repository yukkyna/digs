<?php

namespace Digs\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MemberGroupRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MemberGroupRepository extends EntityRepository
{
	public function findAllInIds($ids)
	{
        return $this->createQueryBuilder('u')
            ->where('u.id IN(:ids)')
			->setParameter('ids', $ids)
            ->getQuery()
			->getResult();
	}
    public function findAllJoinMembers()
    {
        return $this->createQueryBuilder('u')
            ->select('u, m')
            ->leftJoin('u.members', 'm')
            ->getQuery()
			->getResult();
    }
    public function findJoinMembers($id)
    {
        return $this->createQueryBuilder('u')
            ->select('u, m, p')
            ->leftJoin('u.members', 'm')
            ->leftJoin('m.profile', 'p')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->orderBy('m.id', 'ASC')
            ->getQuery()
			->getSingleResult()
            ;
    }
}
