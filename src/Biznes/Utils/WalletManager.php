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
use Biznes\DatabaseBundle\Entity\Withdraws;

/**
 * Description of WalletManager
 *
 * @author Michal
 */
class WalletManager extends Controller{
    protected $em = null;
    
    protected $commissionValue = 0.2;
    
    protected $incomes;
    protected $withdraws;
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
                    $withdraw = new Withdraws();
                    $withdraw->setValue($val);
                    $withdraw->setIdUser($user);
                    $withdraw->setDateWithdraw(new \DateTime);
                    $withdraw->setState('oczekuje');

                    $this->em->persist($withdraw);
                    $this->em->flush();
                    return $withdraw;
                }
            }
            //Else Redirect?
        }
        else{
            throw new Exception('Wallet Manager: EntityManager cant be null.');
        }
        
        return null;
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
    
    protected function loadWithdrawsFromDB(Users $user){
        if($this->em != null){
            $this->withdraws = $this->em->getRepository('BiznesDatabaseBundle:Withdraws')
                    ->findBy(array(
                        'idUser' => $user
                    ), array(
                        'dateWithdraw' => 'ASC',
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
            $this->loadWithdrawsFromDB($user);
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
        $this->loadWithdrawsFromDB($user);
        
        $incomesOverall = 0;
        foreach($this->incomes as $income){
            if($income->getState() == 1){
                $value = $income->getValue();
                $incomesOverall += floatval($value);
            }
        }
        
        $withdrawsOverall = 0;
        foreach($this->withdraws as $withdraw){
            $value = $withdraw->getValue();
            $withdrawsOverall += floatval($value);
        }
        
        $this->moneyInWallet = round(($incomesOverall - $withdrawsOverall), 2);
        
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
            if($income->getState() == 1){
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
        foreach($this->withdraws as $withdraw){
            $count += 1;
            $value += floatval($withdraw->getValue());
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
        foreach($this->withdraws as $withdraw){
            if($withdraw->getState() == 'zrealizowano'){
                $count += 1;
                $value += floatval($withdraw->getValue());
            }
        }
        
        return array(
            'count' => $count,
            'value' => $value,
        );
    }
    
    public function getCountWithdraws(){
        return count($this->withdraws);
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

    public function getWithdraws() {
        return $this->withdraws;
    }
    
    public function getPartners(){
        return $this->partners;
    }

    public function getCountedIncomesByMonthsAsArray(){
        
    }

}
