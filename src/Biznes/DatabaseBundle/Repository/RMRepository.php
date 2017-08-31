<?php

namespace Biznes\DatabaseBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RMRepository extends EntityRepository {

    public function findByAll() {
        return $this->getEntityManager()
                        ->createQuery(
                                'SELECT rm FROM BiznesDatabaseBundle:RealizationMethods rm')
                        ->getResult();
    }

    public function findAllOrderedByName() {
        return $this->getEntityManager()
                        ->createQuery(
                                'SELECT r FROM BiznesDatabaseBundle:RealizationMethods r ORDER BY r.name ASC'
                        )
                        ->getResult();
    }

}
