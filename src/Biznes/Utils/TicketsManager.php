<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Biznes\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Biznes\DatabaseBundle\Entity\Tickets;
use Biznes\DatabaseBundle\Entity\Messages;
use Biznes\DatabaseBundle\Entity\Users;

/**
 * Description of ticketsManager
 *
 * @author Michal
 */
class TicketsManager extends Controller{
    protected $em = null;
    protected $ticket = null;
    
    protected $messages = array();
    protected $tickets = array();
    
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function loadByIdTicket($id){
        $this->ticket = $this->em->getRepository('BiznesDatabaseBundle:Tickets')->
            findOneBy(array(
                'idTicket' => $id,
            ));
        
        $this->loadMessagesByTicket();
        
        return $this;
    }
    
    public function loadAllTicketsByIdUser(Users $user){
        $this->tickets = $this->em->getRepository('BiznesDatabaseBundle:Tickets')
                ->findBy(array(
                    'idUser' => $user,
                ), array(
                        'dateOpen' => 'DESC',
                )); 
    }
    
    public function loadMessagesByTicket(){
        if($this->ticket !== null){
           $ticketId = $this->ticket->getIdTicket();
            $this->messages = $this->em->getRepository('BiznesDatabaseBundle:Messages')
                    ->findBy(array(
                        'idTicket' => $ticketId,
                    ), array(
                        'dateMessage' => 'ASC',
                    )); 
        }
        /*
        else{
            throw new Exception('Ticket cant be null.');
        }   
         * 
         */ 
        return $this;
    }
    
    public function getTicket() {
        return $this->ticket;
    }
    
    public function getMessagesCount(Users $user){
        $userMessages = $this->em->getRepository('BiznesDatabaseBundle:Messages')
                ->findBy(array(
                    'idUser' => $user,
                ));
        /*
        $i = 0;
        foreach($userMessages as $message){
            if($message->getIdUser() == $user){
                array_splice($userMessages, $i, 1);
            }
            $i++;
        }
         * 
         */
        
        return count($userMessages);
    }

    public function getMessages() {
        return $this->messages;
    }
    
    
    public function setTicket(Tickets $ticket) {
        $this->ticket = $ticket;
    }

    public function apendMessage(Messages $message) {
        $this->em->persist($message);
        $this->em->flush();
        $this->messages[] = $message;
    }
    
    
    public function closeTicket(){
        if($this->ticket !== null){
            $this->ticket->setDateClose(new \DateTime);
        }
    }

    public function getAllTickets(){  
        return $this->tickets;
    }
    
    public function getAllOpenTickets(){
        $temp = array();
        foreach($this->tickets as $ticket){
            if($ticket->getDateClose() === null){
                $temp[] = $ticket;
            }
        }
        
        return $temp;
    }
    
    public function getTicketsCount(){
        return count($this->tickets);
    }
    
    public function getOpenTicketsCount(){
        $temp = array();
        foreach($this->tickets as $ticket){
            if($ticket->getDateClose() === null){
                $temp[] = $ticket;
            }
        }
        
        return count($temp);
    }
}
