<?php

namespace Biznes\DatabaseBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProductsRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT * FROM DatabaseBundle:Product ORDER BY name ASC'
            )
            ->getResult();
    }
}