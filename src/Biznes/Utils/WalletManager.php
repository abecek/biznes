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
use Biznes\DatabaseBundle\Entity\Expanses;

/**
 * Description of WalletManager
 *
 * @author Michal
 */
class WalletManager extends Controller{
    protected $em = null;
    
    protected $commissionValue = 0.2;
    
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
            if($user === null) throw new Exception('User could not be null.');
            $income = new Incomes();       
        
            $income->setIdSponsor($sponsorId);
            $income->setIdUserfrom($user);

            $income->setIdOrder($order);
            $income->setIdProduct($product);

            $income->setDateIncome($date);
            $income->setState("do zaakceptowania");

            $value = (intval($product->getPrice()) * $this->commissionValue);
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
    
    public function addWithdraw(Users $user, $form, \DateTime $date = null){
        if($this->em != null){
            if($date === null) $date = new \DateTime;
            if($user === null) throw new Exception('User could not be null.');

            if($form->isValid() && $form->isSubmitted()){
                $val = $form['value']->getData();
                $this->countMoneyInWallet($user);
                
                $moneyAfterWithdraw = ($this->getMoneyInWallet() - floatval($val));
                
                if($val != null && $moneyAfterWithdraw >= 0){ 
                    $expanse = new Expanses();
                    $expanse->setValue($val);
                    $expanse->setIdUser($user);
                    $expanse->setDateExpanse(new \DateTime);
                    $expanse->setState('oczekuje');

                    $this->em->persist($expanse);
                    $this->em->flush();
                    return $expanse;
                }
            }
        }
        else{
            throw new Exception('Wallet Manager: EntityManager cant be null.');
        }
        
        return null;
    }
    
    public function addExpanse(Users $user, $value, \DateTime $date = null){
        if($this->em != null){
            if($date === null) $date = new \DateTime;
            if($user === null) throw new Exception('User could not be null.');
                
            $expanse = new Expanses();
            $expanse->setDateExpanse($date);
            $expanse->setIdUser($user);
            $expanse->setState('oczekuje');
            $value = floatval($value);
            $value = round($value, 2);
            $expanse->setValue($value);
            
            $this->em->persist($expanse);
            $this->em->flush();
            $this->em->clear();
        }
        else{
            throw new Exception('Wallet Manager: EntityManager cant be null.');
        }
        
    }
    
    protected function loadIncomesFromDB(Users $user){
        if($this->em != null){
            $userId = $user->getIdUser();
            $this->incomes = $this->em->getRepository('BiznesDatabaseBundle:Incomes')
                    ->findBy(array(
                        'idSponsor' => $userId
                    ), array(
                        'dateIncome' => 'ASC',
                    ));
        }
        else{
            throw new Exception('Wallet Manager: EntityManager cant be null.');
        }
        return $this;
    }
    
    protected function loadExpansesFromDB(Users $user){
        if($this->em != null){
            $this->expanses = $this->em->getRepository('BiznesDatabaseBundle:Expanses')
                    ->findBy(array(
                        'idUser' => $user
                    ), array(
                        'dateExpanse' => 'ASC',
                    ));
        }
        else{
            throw new Exception('Wallet Manager: EntityManager cant be null.');
        }
        return $this;
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
        return $this;
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
        return $this;
    }
    
    
    /*
     * Return counted wallet balance
     */
    public function countMoneyInWallet(Users $user){
        $this->loadIncomesFromDB($user);
        $this->loadExpansesFromDB($user);
        
        $incomesOverall = 0;
        foreach($this->incomes as $income){
            if($income->getState() == 'zaakceptowano'){
                $value = $income->getValue();
                $incomesOverall += floatval($value);
            }
        }
        
        $expansesOverall = 0;
        foreach($this->expanses as $expanse){
            $value = $expanse->getValue();
            $expansesOverall += floatval($value);
        }
        
        $this->moneyInWallet = round(($incomesOverall - $expansesOverall), 2);
        
        return $this;
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
            if($income->getState() == 'zaakceptowano'){
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
            if($expanse->getState() == 'zrealizowano'){
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

    public function getCountedIncomesByMonthsAsArray(){
        
    }

}
