<?php


/**
 * Description of User
 *
 * @author Michal
 */

namespace Biznes\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Biznes\DatabaseBundle\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Session\Session;

use Doctrine\ORM\EntityManager;

use Biznes\DatabaseBundle\Entity\Users;
use Biznes\DatabaseBundle\Entity\UsersData;
use Biznes\DatabaseBundle\Entity\UsersAddresses;



class UserManager extends Controller{
    protected $user = null;
    protected $userData = null;
    protected $userAddress = null;
    protected $em;


    public function __construct(EntityManager $em){
        $this->em = $em;
    }
    
    public function loadDataFromUser(Users $user = null){
        if($user != null){
            $em = $this->em->getRepository('BiznesDatabaseBundle:UsersData');

            $this->user = $user;
            $this->userData = $em->findOneByIdUser($user);
            $em = $this->em->getRepository('BiznesDatabaseBundle:UsersAddresses');
            $this->userAddress = $em->findOneByIdUser($user);
        }
    }
    
    public function setUser(Users $user){
        $this->user = $user;
    }
    
    public function userExists(){
        return $this->user != null ? true : false;
    }
    
    public function userDataExists(){
        return $this->userData != null ? true : false;
    }
    
    public function userAddressExists(){
        return $this->userAddress != null ? true : false;
    }
    
    public function createUser($data){
        if(!empty($data)){
            $user = new Users();
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($data['plainPassword'])
                    ->setUsername($data['username'])
                    ->setEmail($data['email'])
                    ->setDateRegister(new \DateTime())
                    ->setGender($data['gender']);
            if(in_array('idSponsor', $data)){
                $user->setIdSponsor($data['idSponsor']);
            }
            $this->em->persist($user);
            $this->em->flush();
        }
    }
    
    public function createUserData($data){
        
    }
    
    public function get($key){
        switch($key){
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
}
