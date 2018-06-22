<?php

namespace Biznes\DatabaseBundle\Repository;
use Doctrine\ORM\EntityRepository;

class ProductsRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
                        ->createQuery(
                                'SELECT * FROM DatabaseBundle:Products ORDER BY name ASC'
                        )
                        ->getResult();
    }

    public function findOneById($id) {
        return $this->getEntityManager()
                        ->createQuery(
                                'SELECT p FROM DatabaseBundle:Products p WHERE p.idProduct = :id'
                        )->setParameter('id', $id)
                        ->getResult();
    }

}
