<?php

namespace Biznes\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Biznes\DatabaseBundle\Entity\Expanses;
use Biznes\DatabaseBundle\Form\ExpansesType;
use Biznes\Utils\WalletManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction() {
        $user = $this->getUser();
        $userId = null;
        $ticketsCount = 0;
        $notificationCount = 0;
        
        $tickets = null;
        $notes = null;
        
        $em = $this->getDoctrine()->getManager();
        
        if ($user != null) {
            $userId = $user->getIdUser();
            $tm = $this->get('ticketsManager');
            $tm->loadAllTicketsByIdUser($user);
            $tickets = $tm->getAllOpenTickets();
            $ticketsCount = $tm->getOpenTicketsCount($user);
            
            $notes = $em->getRepository('BiznesDatabaseBundle:Notifications')
                ->findBy(array(), array(
                    'dateNotification' => 'DESC',
                ));
            $notificationCount = count($notes);
        }

        $um = $this->get('userManager');
        $um->loadDataFromUser($user);
        return $this->render('BiznesServiceBundle:Default:index.html.twig', array(
                    'ticketsCount' => $ticketsCount,
                    'notificationCount' => $notificationCount,
                    'notes' => $notes,
                    'tickets' => $tickets,
        ));
    }

    /**
     * @Route("/created", name="accountCreatedService")
     */
    public function createdAction() {
        return $this->render('BiznesServiceBundle:Default:registerCreated.html.twig');
    }

    /**
     * @Route("/logged", name="logged")
     */
    public function loggedAction() {
        $user = $this->getUser();

        if ($user == null)
            $roles = array('Brak');
        else
            $roles = $user->getRoles();

        return $this->render('BiznesServiceBundle:Default:logged.html.twig', array(
                    'role' => $roles
        ));
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction() {
        $user = $this->getUser();
        return $this->render('BiznesServiceBundle:Default:logged.html.twig', array(
                    'role' => $user->getRoles()
        ));
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction() {
        $user = $this->getUser();
        if ($user != null) {
            $em = $this->getDoctrine()->getManager();
            $wm = $this->get('walletManager');

            $wm->countMoneyInWallet($user);
            $moneyInWallet = $wm->getMoneyInWallet();

            return $this->render('BiznesServiceBundle:Default:dashboard.html.twig', array(
                        'moneyInWallet' => $moneyInWallet,
                        'wm' => $wm,
            ));
        } else {
            throw $this->createAccessDeniedException('You must be logged in to see this page.');
        }
    }

    /**
     * @Route("/incomes", name="incomes")
     */
    public function incomesAction() {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }

        $wm = $this->get('walletManager');
        $user = $this->getUser();
        $incomes = $wm->loadWalletManagerFromDB($user)->getIncomes();

        return $this->render('BiznesServiceBundle:Default:incomes.html.twig', array(
                    'incomes' => $incomes,
        ));
    }

    /**
     * @Route("/withdraws", name="withdraws")
     */
    public function expansesAction() {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }

        $wm = $this->get('walletManager');
        $user = $this->getUser();
        $expanses = $wm->loadWalletManagerFromDB($user)->getExpanses();

        return $this->render('BiznesServiceBundle:Default:expanses.html.twig', array(
                    'expanses' => $expanses,
        ));
    }

    /**
     * @Route("/newWithdraw", name="newWithdraw")
     */
    public function newWithdrawAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }
        $msg = null;

        $em = $this->getDoctrine()->getManager();
        $wm = new WalletManager($em);
        $wm->countMoneyInWallet($this->getUser());

        $availableMoney = $wm->getMoneyInWallet();

        $form = $this->createForm(ExpansesType::class, null, array(
            'attr' => array(
                'id' => 'withdrawForm',
                'class' => 'form',
            ),
        ));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($wm->addWithdraw($this->getUser(), $form, new \DateTime)) {
                $msg = 'Wypłata została przekazana do realizacji.';
            } else {
                $msg = 'Wprowadzone dane były błedne lub nie posiadasz wystarczającej ilości pieniędzy.';
            }

            $form = $this->createForm(ExpansesType::class, null, array(
                'attr' => array(
                    'id' => 'withdrawForm',
                    'class' => 'form',
                ),
            ));
        }

        return $this->render('BiznesServiceBundle:Default:newWithdraw.html.twig', array(
                    'newWithdrawForm' => $form->createView(),
                    'moneyInWallet' => $availableMoney,
                    'msg' => $msg,
        ));
    }

    /**
     * @Route("/support", name="support")
     */
    public function supportAction() {

        return $this->render('BiznesServiceBundle:Default:support.html.twig', array(
        ));
    }

    /**
     * @Route("/support/newticket", name="newTicket")
     */
    public function newTicketAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }
        $msg = null;

        $ticket = new \Biznes\DatabaseBundle\Entity\Tickets();
        $form = $this->createFormBuilder($ticket)
                        ->add('title', TextType::class, array(
                            'attr' => array('class' => 'form-control'),
                            'label' => 'Tytuł:',
                        ))
                        ->add('text', TextareaType::class, array(
                            'attr' => array(
                                'rows' => "6",
                                'class' => 'form-control'
                            ),
                            'label' => 'Treść:',
                        ))
                        ->add('createTicket', SubmitType::class, array(
                            'label' => 'Załóż nowy wątek!',
                            'attr' => array(
                                'class' => 'btn btn-primary btn-block',
                    )))->getForm();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $ticket->setIdUser($this->getUser());
            $ticket->setDateOpen(new \DateTime);

            $em->persist($ticket);
            $em->flush();

            return $this->redirectToRoute('messages');
        }

        /*
          if ($request->getMethod() == 'POST') {
          $em = $this->getDoctrine()->getManager();
          $ticket = new \Biznes\DatabaseBundle\Entity\Tickets();
          $ticket->setIdUser($this->getUser());

          $ticket->setTitle($request->request->get('title'));
          $ticket->setText($request->request->get('text'));
          $ticket->setDateOpen(new \DateTime);
          $em->persist($ticket);
          $em->flush();
          }
         */

        return $this->render('BiznesServiceBundle:Default:newTicket.html.twig', array(
                    'ticketForm' => $form->createView(),
        ));
    }

    /**
     * @Route("/support/messages", name="messages")
     */
    public function messagesAction() {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }
        $em = $this->getDoctrine()->getManager();
        $tickets = $em->getRepository('BiznesDatabaseBundle:Tickets')
                ->findBy(array(
            'idUser' => $this->getUser(),
        ));

        return $this->render('BiznesServiceBundle:Default:messages.html.twig', array(
                    'tickets' => $tickets,
        ));
    }

    /**
     * @Route("/support/message/{id}", name="message", requirements={"referer": "\d+"})
     */
    public function messageAction(Request $request, $id = null) {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }
        if ($id === null) {
            throw new Exception('There is no ticket with this id.');
        }
        $em = $this->getDoctrine()->getManager();
        $ticket = $em->getRepository('BiznesDatabaseBundle:Tickets')
                ->findOneBy(array(
            'idTicket' => $id,
        ));

        
        $tm = $this->get('ticketsManager');
        $tm->setTicket($ticket);
        
        $message = new \Biznes\DatabaseBundle\Entity\Messages();
        $message->setIdUser($this->getUser());
        $message->setIdTicket($ticket);
        
        $form = $this->createFormBuilder($message)
                        ->add('text', TextareaType::class, array(
                            'attr' => array(
                                'rows' => "6",
                                'class' => 'form-control'
                            ),
                            'label' => 'Treść:',
                        ))
                        ->add('sendMessage', SubmitType::class, array(
                            'label' => 'Wyślij wiadomość!',
                            'attr' => array(
                                'class' => 'btn btn-primary btn-block',
                    )))->getForm();
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $message->setDateMessage(new \DateTime);
//            $em->persist($message);
//            $em->flush();
            
            $tm->apendMessage($message);
            
            return $this->redirectToRoute('message', array(
                'id' => $id,
            ));
        }
        
//        $messages = $em->getRepository('BiznesDatabaseBundle:Messages')
//                ->findBy(array(
//            'idTicket' => $ticket->getIdTicket(),
//        ));
        
        $messages = $tm->loadMessagesByTicket()->getMessages();        

        return $this->render('BiznesServiceBundle:Default:message.html.twig', array(
                    'ticket' => $ticket,
                    'messages' => $messages,
                    'messageForm' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/notatifications", name="notatifications")
     */
    public function notatificationsAction(){
        $notes = array();
        $em = $this->getDoctrine()->getManager();
        $notes = $em->getRepository('BiznesDatabaseBundle:Notifications')
                ->findBy(array(), array(
                    'dateNotification' => 'DESC',
                ));
        
        return $this->render('BiznesServiceBundle:Default:notifications.html.twig', array(
            'notes' => $notes,
        ));
    }
    
    /**
     * @Route("/personalInfo", name="personalDataInfo")
     */
    public function personalInfoAction() {
        return $this->render('BiznesServiceBundle:Default:personalDataInfo.html.twig');
    }
}
