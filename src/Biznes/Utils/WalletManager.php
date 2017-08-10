<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Biznes\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;

use Biznes\DatabaseBundle\Entity\Users;
use Biznes\DatabaseBundle\Entity\Products;
use Biznes\DatabaseBundle\Entity\Orders;
use Biznes\DatabaseBundle\Entity\Incomes;

/**
 * Description of WalletManager
 *
 * @author Michal
 */
class WalletManager extends Controller{
    protected $em;
    
    protected $incomes;
    protected $expanses;
    
    protected $moneyInWallet;

    public function __construct(EntityManager $em){
        $this->em = $em;        
    }
    
    /*
     * param integer $sponsorId
     * Id of user, who gets income
     * Is workaround becuse Doctrine wasnt pushing object Income into database when
     * user and sponsor were objects of Users class, one of them was always null
     * I dont know why, even when i tried get Sponsor from User object, by getIdSponsor function
     * This required changes in config Incomes.orm.xml and entity Incomes.php
     * Now idSponsor is type of integer, not object of entity Users
     * 
     * param $user
     * Is user from whom we get income
     */
    public function addIncome($sponsorId, Users $user, Orders $order, \DateTime $date, Products $product){
        $income = new Incomes();       
        
        $income->setIdSponsor($sponsorId);
        $income->setIdUserfrom($user);
        
        $income->setIdOrder($order);
        $income->setIdProduct($product);
        
        $income->setDateIncome($date);
        $income->setState("to accept");
        
        $value = (intval($product->getPrice()) * 0.1);
        $value = round($value, 2);
        $value = strval($value);
        
        $income->setValue($value);

        $this->em->merge($income);
                
        $this->em->flush();
        $this->em->clear();      
    }
    
    protected function loadIncomesFromDB(Users $user){
        $userId = $user->getIdUser();
        $this->incomes = $this->em->getRepository('BiznesDatabaseBundle:Incomes')
                ->findBy(array(
                    'idSponsor' => $userId
                ));
    }
    
    protected function loadExpansesFromDB(Users $user){
        $this->expanses = $this->em->getRepository('BiznesDatabaseBundle:Expanses')
                ->findBy(array(
                    'idUser' => $user
                ));
    }
    
    public function countMoneyInWallet(Users $user){
        $this->loadIncomesFromDB($user);
        $this->loadExpansesFromDB($user);
        
        $incomesOverall = 0;
        foreach($this->incomes as $income){
            $value = $income->getValue();
            $incomesOverall += floatval($value);
        }
        
        $expansesOverall = 0;
        foreach($this->expanses as $expanse){
            $value = $expanse->getValue();
            $expansesOverall += floatval($expanse);
        }
        
        $this->moneyInWallet = round(($incomesOverall - $expansesOverall), 2);
    }
    
    public function getMoneyInWallet(){
        return $this->moneyInWallet;
    }
    
    function getIncomes() {
        return $this->incomes;
    }

    function getExpanses() {
        return $this->expanses;
    }


}
