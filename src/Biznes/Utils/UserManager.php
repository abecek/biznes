<?php

/**
 * Description of User
 *
 * @author Michal
 */

namespace Biznes\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Biznes\DatabaseBundle\Entity\Users;
use Biznes\DatabaseBundle\Entity\UsersData;
use Biznes\DatabaseBundle\Entity\UsersAddresses;

class UserManager extends Controller {

    protected $user = null;
    protected $userData;
    protected $userAddress;
    protected $em = null;

    public function __construct(EntityManager $em) {
        $this->user = new Users();
        $this->userData = new UsersData();
        $this->userAddress = new UsersAddresses();
        $this->em = $em;
    }

    //USED AFTER LOGIN IN, AFTER REDIRECT ROUTES{homepage, shop}
    public function loadDataFromUser(Users $user = null) {
        if ($user != null) {
            $em = $this->em->getRepository('BiznesDatabaseBundle:UsersData');

            $this->user = $user;
            $this->userData = $em->findOneByIdUser($user);
            if ($this->userData == null) {
                $this->userData = new UsersData();
            }
            $em = $this->em->getRepository('BiznesDatabaseBundle:UsersAddresses');
            $this->userAddress = $em->findOneByIdUser($user);
            if ($this->userAddress == null) {
                $this->userAddress = new UsersAddresses();
            }
        }
    }

    public function getBoughtIdProducts(Users $user = null) {
        $boughtProductsArray = array();

        if ($user !== null) {
            if ($this->em !== null) {
                $orders = $this->em->getRepository('BiznesDatabaseBundle:Orders')
                        ->findBy(array(
                    'idUser' => $user->getIdUser(),
                ));

                $carts = array();
                foreach ($orders as $order) {
                    $carts[] = $this->em->getRepository('BiznesDatabaseBundle:Carts')
                            ->findBy(array(
                        'idOrder' => $order->getIdOrder(),
                    ));
                }

                foreach ($carts as $cart) {
                    foreach ($cart as $cartProd) {
                        $product = $cartProd->getIdProduct();
                        $boughtProductsArray[] = $product->getIdProduct();
                    }
                }
            } else {
                throw new Exception("Entity manager is null.");
            }
        } else {
            throw new Exception("User is null.");
        }

        return $boughtProductsArray;
    }

    public function setUser(Users $user) {
        $this->user = $user;
    }

    public function userExists() {
        if ($this->user->getIdUser() != null)
            return true;
        return false;
    }

    public function userDataExists() {
        if ($this->userData->getIdUserData() != null)
            return true;
        return false;
    }

    public function userAddressExists() {
        if ($this->userAddress->getIdUserAddress() != null)
            return true;
        return false;
    }

    public function createUser($data) {
        if (!empty($data)) {
            $user = new Users();
            $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($data['plainPassword'])
                    ->setUsername($data['username'])
                    ->setEmail($data['email'])
                    ->setDateRegister(new \DateTime())
                    ->setGender($data['gender']);
            if (in_array('idSponsor', $data)) {
                $user->setIdSponsor($data['idSponsor']);
            }
            $this->em->persist($user);
            $this->em->flush();
        }
    }

    public function get($key) {
        switch ($key) {
            case 'user':
                return $this->user;
                break;
            case 'userAddress':
                return $this->userAddress;
                break;
            case 'userData':
                return $this->userData;
                break;
            default:
                throw new Exception('Param dont match any property.');
                break;
        }
    }

    public function getUserData() {
        return $this->userData;
    }

    public function getUserAddress() {
        return $this->userAddress;
    }

}
