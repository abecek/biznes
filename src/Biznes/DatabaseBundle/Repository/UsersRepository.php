<?php

/*
 *  Michał Błaszczyk
 */

namespace Biznes\DatabaseBundle\Repository;

use Doctrine\ORM\EntityRepository;
/**
 * Description of UsersRepository
 *
 * @author Michal
 */
class UsersRepository extends EntityRepository {
    
    public function findOneById($id) {
        return $this->getEntityManager()
                        ->createQuery(
                                'SELECT u FROM DatabaseBundle:Users u WHERE u.idUser = :id'
                        )->setParameter('id', $id)
                        ->getResult();
    }
    
}
