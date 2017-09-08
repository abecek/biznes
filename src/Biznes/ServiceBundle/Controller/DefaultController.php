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
        $ticketsCount = 0;
        $notificationCount = 0;      
        $tickets = null;
        $notes = null;
               
        if ($user != null) {
            $tm = $this->get('ticketsManager');
            $tm->loadAllTicketsByIdUser($user);
            $tm->loadNotifications();
            $tickets = $tm->getAllOpenTickets();
            $ticketsCount = $tm->getOpenTicketsCount($user);
            
            $notes = $tm->getNotes();
            $notificationCount = $tm->getNotesCount();
        }

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
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction() {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }
        
        $user = $this->getUser();
        $ticketsCount = 0;
        $notificationCount = 0;  
        $tickets = null;
        $notes = null;
        $incomesCount = 0;
        $withdrawsCount = 0;
               
        if ($user != null) {
            $tm = $this->get('ticketsManager');
            $tm->loadAllTicketsByIdUser($user);
            $tm->loadNotifications();
            $tickets = $tm->getAllOpenTickets();
            $ticketsCount = $tm->getOpenTicketsCount($user);
            
            $notes = $tm->getNotes();
            $notificationCount = $tm->getNotesCount();
            
            $wm = $this->get('walletManager');

            $wm->countMoneyInWallet($user);
            $moneyInWallet = $wm->getMoneyInWallet();
            $incomesCount = $wm->getCountIncomes();
            $withdrawsCount = $wm->getCountExpanses();

            return $this->render('BiznesServiceBundle:Default:dashboard.html.twig', array(
                        'moneyInWallet' => $moneyInWallet,
                        'incomesCount' => $incomesCount,
                        'withdrawsCount' => $withdrawsCount,
                        'ticketsCount' => $ticketsCount,
                        'notificationCount' => $notificationCount,
                        'notes' => $notes,
                        'tickets' => $tickets,
            ));
        } 
        else{
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
          
        $incomes = null;
        $ticketsCount = 0;
        $notificationCount = 0;
        
        $tickets = null;
        $notes = null;
        $user = $this->getUser();
        
        if ($user != null) {
            $wm = $this->get('walletManager');
            $incomes = $wm->loadWalletManagerFromDB($user)->getIncomes();
            
            $tm = $this->get('ticketsManager');
            $tm->loadAllTicketsByIdUser($user);
            $tm->loadNotifications();
            $tickets = $tm->getAllOpenTickets();
            $ticketsCount = $tm->getOpenTicketsCount($user);
            
            $notes = $tm->getNotes();
            $notificationCount = $tm->getNotesCount();
        }
        

        return $this->render('BiznesServiceBundle:Default:incomes.html.twig', array(
                    'incomes' => $incomes,
                    'ticketsCount' => $ticketsCount,
                    'notificationCount' => $notificationCount,
                    'notes' => $notes,
                    'tickets' => $tickets,
        ));
    }

    /**
     * @Route("/withdraws", name="withdraws")
     */
    public function expansesAction() {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }

        $expanses = null;
        
        $ticketsCount = 0;
        $notificationCount = 0;      
        $tickets = null;
        $notes = null;
        
        $user = $this->getUser();       
        if ($user != null) {
            $wm = $this->get('walletManager');
            $expanses = $wm->loadWalletManagerFromDB($user)->getExpanses();
            
            $tm = $this->get('ticketsManager');
            $tm->loadAllTicketsByIdUser($user);
            $tm->loadNotifications();
            $tickets = $tm->getAllOpenTickets();
            $ticketsCount = $tm->getOpenTicketsCount($user);
            
            $notes = $tm->getNotes();
            $notificationCount = $tm->getNotesCount();
        }

        return $this->render('BiznesServiceBundle:Default:expanses.html.twig', array(
                    'expanses' => $expanses,
                    'ticketsCount' => $ticketsCount,
                    'notificationCount' => $notificationCount,
                    'notes' => $notes,
                    'tickets' => $tickets,
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
        
        $availableMoney = null;
        $ticketsCount = 0;
        $notificationCount = 0;      
        $tickets = null;
        $notes = null;
       
        $user = $this->getUser();
        if ($user != null) {
            $wm = new WalletManager($em);
            $wm->countMoneyInWallet($user);
            $availableMoney = $wm->getMoneyInWallet();
            
            $tm = $this->get('ticketsManager');
            $tm->loadAllTicketsByIdUser($user);
            $tm->loadNotifications();
            $tickets = $tm->getAllOpenTickets();
            $ticketsCount = $tm->getOpenTicketsCount($user);
            
            $notes = $tm->getNotes();
            $notificationCount = $tm->getNotesCount();
        }

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
                    'ticketsCount' => $ticketsCount,
                    'notificationCount' => $notificationCount,
                    'notes' => $notes,
                    'tickets' => $tickets,
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
        
        $ticketsCount = 0;
        $notificationCount = 0;      
        $tickets = null;
        $notes = null;
             
        $user = $this->getUser();
        if ($user != null) {
            $tm = $this->get('ticketsManager');
            $tm->loadAllTicketsByIdUser($user);
            $tm->loadNotifications();
            $tickets = $tm->getAllOpenTickets();
            $ticketsCount = $tm->getOpenTicketsCount($user);
            
            $notes = $tm->getNotes();
            $notificationCount = $tm->getNotesCount();
        }

        return $this->render('BiznesServiceBundle:Default:newTicket.html.twig', array(
                    'ticketForm' => $form->createView(),
                    'ticketsCount' => $ticketsCount,
                    'notificationCount' => $notificationCount,
                    'notes' => $notes,
                    'tickets' => $tickets,
        ));
    }

    /**
     * @Route("/support/messages", name="messages")
     */
    public function messagesAction() {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }
        
        $ticketsCount = 0;
        $notificationCount = 0;      
        $tickets = null;
        $notes = null;
               
        $user = $this->getUser();
        if ($user != null) {
            $tm = $this->get('ticketsManager');
            $tm->loadAllTicketsByIdUser($user);
            $tm->loadNotifications();
            $tickets = $tm->getTickets();
            $ticketsCount = $tm->getOpenTicketsCount($user);
            
            $notes = $tm->getNotes();
            $notificationCount = $tm->getNotesCount();
        }

        return $this->render('BiznesServiceBundle:Default:messages.html.twig', array(
                    'tickets' => $tickets,
                    'ticketsCount' => $ticketsCount,
                    'notificationCount' => $notificationCount,
                    'notes' => $notes,
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
        
        $ticketsCount = 0;
        $notificationCount = 0;      
        $tickets = null;
        $ticket = null;
        $notes = null;
        
        $user = $this->getUser();
        if ($user != null) {
            $tm = $this->get('ticketsManager');
            $tm->loadAllTicketsByIdUser($user);
            $tm->loadNotifications();
            $tickets = $tm->getAllOpenTickets();
            $ticketsCount = $tm->getOpenTicketsCount($user);
            
            $ticket = $tm->loadByIdTicket($id)->getTicket();
            
            $notes = $tm->getNotes();
            $notificationCount = $tm->getNotesCount();
        }
       
        
        $message = new \Biznes\DatabaseBundle\Entity\Messages();
        $message->setIdUser($this->getUser());
        $message->setIdTicket($ticket);
        
        $form = $this->createFormBuilder($message)
                        ->add('text', TextareaType::class, array(
                            'attr' => array(
                                'rows' => "6",
                                'class' => 'form-control'
                            ),
                            'label' => 'Treść nowej wiadomośći:',
                        ))
                        ->add('sendMessage', SubmitType::class, array(
                            'label' => 'Wyślij wiadomość!',
                            'attr' => array(
                                'class' => 'btn btn-primary btn-block',
                    )))->getForm();
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $message->setDateMessage(new \DateTime);           
            $tm->apendMessage($message);
            
            return $this->redirectToRoute('message', array(
                'id' => $id,
            ));
        }
        
        $messages = $tm->loadMessagesByTicket()->getMessages();        

        return $this->render('BiznesServiceBundle:Default:message.html.twig', array(
                    'ticket' => $ticket,
                    'messages' => $messages,
                    'messageForm' => $form->createView(),
                    'ticketsCount' => $ticketsCount,
                    'notificationCount' => $notificationCount,
                    'notes' => $notes,
                    'tickets' => $tickets,
        ));
    }
    
    /**
     * @Route("/notatifications", name="notatifications")
     */
    public function notatificationsAction(){
        $user = $this->getUser();
        $ticketsCount = 0;
        $notificationCount = 0;      
        $tickets = null;
        $notes = null;
               
        if ($user != null) {
            $tm = $this->get('ticketsManager');
            $tm->loadAllTicketsByIdUser($user);
            $tm->loadNotifications();
            $tickets = $tm->getAllOpenTickets();
            $ticketsCount = $tm->getOpenTicketsCount($user);
            
            $notes = $tm->getNotes();
            $notificationCount = $tm->getNotesCount();
        }
        
        
        return $this->render('BiznesServiceBundle:Default:notifications.html.twig', array(
                    'notes' => $notes,
                    'ticketsCount' => $ticketsCount,
                    'notificationCount' => $notificationCount,
                    'notes' => $notes,
                    'tickets' => $tickets,
        ));
    }
    
    /**
     * @Route("/chart", name="getChart")
     */
    public function chartAction(Request $request, $idUser = null){
        $totalIncomesValue = null;
        $verifiedIncomesValue = null;
        $withdrawsValue = null;
        
        /*
        $em = $this->getDoctrine()->getManager();
        $user = null;
        if(is_numeric($idUser)){
            $user = $em->getRepository('BiznesDatabaseBundle:Users')
                    ->findOneBy(array('idUser' => $idUser));
            if($user === null){
                exit;
            }
        }
         * 
         */
        
        $user = $this->getUser();
        
        $wm = $this->get('walletManager');
        $wm->loadWalletManagerFromDB($user);
        
        $incomes = $wm->getIncomes();
        
        $incomesByMonths = array();
        foreach($incomes as $income){
            $date = $income->getDateIncome();
            $month = $date->format('F');
            $month = strval($month);
            
            if(!isset($incomesByMonths[$month])){
                $incomesByMonths[$month] = 0;
            }
            $floatVal = floatval($incomesByMonths[$month]);
            $val = ($floatVal + $income->getValue());
            $incomesByMonths[$month] = $val;

        }
        
        $incomesTotalArray = $wm->getCountedIncomesAsArray();
        $totalIncomesValue = $incomesTotalArray['value'];
        
        $incomesVerifiedArray = $wm->getCountedVerifiedIncomesAsArray();
        $verifiedIncomesValue = $incomesVerifiedArray['value'];
        
        $withdrawsArray = $wm->getCountedWithdrawsAsArray();
        $withdrawsValue = $withdrawsArray['value'];
        
        
        $data = array(
            'totalIncomesValue' => $totalIncomesValue,
            'verifiedIncomesValue' => $verifiedIncomesValue,
            'withdrawsValue' => $withdrawsValue,
            'incomesByMonths' => $incomesByMonths,
        );
        
        $objJson = new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);
        return $objJson;
    }
}
