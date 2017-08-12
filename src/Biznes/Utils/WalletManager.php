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
    protected $em = null;
    
    protected $incomes;
    protected $expanses;
    protected $partners;
    
    protected $moneyInWallet = 0;

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
        if($this->em != null){
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
        else{
            throw new Exception('Wallet Manager: EntityManager cant be null.');
        }
    }
    
    public function addExpanse(Users $user, $value, \DateTime $date = null){
        if($date === null) $date = new \DateTime;
        
    }
    
    protected function loadIncomesFromDB(Users $user){
        if($this->em != null){
            $userId = $user->getIdUser();
            $this->incomes = $this->em->getRepository('BiznesDatabaseBundle:Incomes')
                    ->findBy(array(
                        'idSponsor' => $userId
                    ));
        }
        else{
            throw new Exception('Wallet Manager: EntityManager cant be null.');
        }
    }
    
    protected function loadExpansesFromDB(Users $user){
        if($this->em != null){
            $this->expanses = $this->em->getRepository('BiznesDatabaseBundle:Expanses')
                    ->findBy(array(
                        'idUser' => $user
                    ));
        }
        else{
            throw new Exception('Wallet Manager: EntityManager cant be null.');
        }
    }
    
    protected function loadPartnersFromDB(Users $user){
        if($this->em != null){
            $this->partners = $this->em->getRepository('BiznesDatabaseBundle:Users')
                    ->findBy(array(
                        'idSponsor' => $user,
                    ));
        }
        else{
            throw new Exception('Wallet Manager: EntityManager cant be null.');
        }
    }
    
    public function loadWalletManagerFromDB(Users $user){
        if($this->em != null){
            $this->loadIncomesFromDB($user);
            $this->loadExpansesFromDB($user);
            $this->loadPartnersFromDB($user);
        }
        else{
            throw new Exception('Wallet Manager: EntityManager cant be null.');
        }
    }
    
    
    /*
     * Return counted wallet balance
     */
    public function countMoneyInWallet(Users $user){
        $this->loadIncomesFromDB($user);
        $this->loadExpansesFromDB($user);
        
        $incomesOverall = 0;
        foreach($this->incomes as $income){
            if($income->getState() == 'accepted'){
                $value = $income->getValue();
                $incomesOverall += floatval($value);
            }
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
    
    public function getCountIncomes(){
        return count($this->incomes);
    }
    
    /*
     * Return assiociative array(
     *      count => count,
     *      value => value
     * )
     */
    public function getCountedIncomesAsArray(){
        $count = 0;
        $value = 0;
        foreach($this->incomes as $income){
                $count += 1;
                $value += floatval($income->getValue());
        }
        
        return array(
            'count' => $count,
            'value' => $value,
        );
    }
    
    /*
     * Return assiociative array(
     *      count => count,
     *      value => value
     * )
     */
    public function getCountedVerifiedIncomesAsArray(){
        $count = 0;
        $value = 0;
        foreach($this->incomes as $income){
            if($income->getState() == 'accepted'){
                $count += 1;
                $value += floatval($income->getValue());
            }
        }
        
        return array(
            'count' => $count,
            'value' => $value,
        );
    }
    
    /*
     * Return assiociative array(
     *      count => count,
     *      value => value
     * )
     */
    public function getCountedWithdrawsAsArray(){
        $count = 0;
        $value = 0;
        foreach($this->expanses as $expanse){
            $count += 1;
            $value += floatval($expanse->getValue());
        }
        
        return array(
            'count' => $count,
            'value' => $value,
        );
    }
    
    /*
     * Return assiociative array(
     *      count => count,
     *      value => value
     * )
     */
    public function getCountedCompletedWithdrawsAsArray(){
        $count = 0;
        $value = 0;
        foreach($this->expanses as $expanse){
            if($expanse->getState() == 'completed'){
                $count += 1;
                $value += floatval($expanse->getValue());
            }
        }
        
        return array(
            'count' => $count,
            'value' => $value,
        );
    }
    
    public function getCountExpanses(){
        return count($this->expanses);
    }
    
    public function getCountPartners(){
        return count($this->partners);
    }
    
    public function getCountActivePartners(){
        $val = 0;
        foreach ($this->partners as $partner) {
            if($partner->getIsActive() == 1){
                $val += 1;
            }
        }
        return $val;
    }
    
    public function getIncomes() {
        return $this->incomes;
    }

    public function getExpanses() {
        return $this->expanses;
    }
    
    public function getPartners(){
        return $this->partners;
    }


}
