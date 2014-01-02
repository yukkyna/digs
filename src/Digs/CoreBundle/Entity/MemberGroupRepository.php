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
}