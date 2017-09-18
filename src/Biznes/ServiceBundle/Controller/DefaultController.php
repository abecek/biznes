<?php

namespace Biznes\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Biznes\DatabaseBundle\Entity\Expanses;
use Biznes\DatabaseBundle\Form\ExpansesType;
use Biznes\DatabaseBundle\Entity\Users;
use Biznes\Utils\WalletManager;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller {
    
    public function getTicketsAndNotes(Users $user){
        $data = array();
        $data['tickets'] = null;
        $data['notes'] = null;
        $data['ticketsCount'] = 0;
        $data['notificationCount'] = 0;
        
        $data['incomesCount'] = 0;
        $data['withdrawsCount'] = 0;

        if ($user != null) {
            $tm = $this->get('ticketsManager');
            $tm->loadAllTicketsByIdUser($user);
            $tm->loadNotifications();
            $data['tickets'] = $tm->getAllOpenTickets();
            $data['ticketsCount'] = $tm->getOpenTicketsCount($user);

            $data['notes'] = $tm->getNotes();
            $data['notificationCount'] = $tm->getNotesCount();
        }
        
        return $data;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction() {
        $user = $this->getUser();
        $idUser = null;
        $data = null;
        $availableMoney = null;
        $username = null;

        if($user !== null){
            $data = $this->getTicketsAndNotes($user);
            $idUser = $user->getIdUser();
            $em = $this->getDoctrine()->getManager();
            $wm = new WalletManager($em);
            $wm->countMoneyInWallet($user);
            $availableMoney = $wm->getMoneyInWallet();
            $username = $user->getUsername();
        }

        return $this->render('BiznesServiceBundle:Default:index.html.twig', array(
                    'ticketsCount' => $data['ticketsCount'],
                    'notificationCount' => $data['notificationCount'],
                    'notes' => $data['notes'],
                    'tickets' => $data['tickets'],
            
                    'idUser' => $idUser,
                    'username' => $username,
                    'moneyInWallet' => $availableMoney,
        ));
    }

    /**
     * @Route("/created", name="accountCreatedService")
     */
    public function createdAction() {

        
        return $this->render('BiznesServiceBundle:Default:registerCreated.html.twig', array(
            
        ));
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction() {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }

        $user = $this->getUser();
        $data = $this->getTicketsAndNotes($user);
        
        $incomesCount = 0;
        $withdrawsCount = 0;

        if ($user != null) {
            $wm = $this->get('walletManager');

            $wm->countMoneyInWallet($user);
            $moneyInWallet = $wm->getMoneyInWallet();
            $incomesCount = $wm->getCountIncomes();
            $withdrawsCount = $wm->getCountExpanses();


            $form = $this->createFormBuilder(null, array())
                            ->setMethod('POST')
                            ->setAction($this->generateUrl('getChart'))
                            ->add('id', HiddenType::class, array(
                                'empty_data' => $this->getUser()->getIdUser(),
                            ))->getForm();

            return $this->render('BiznesServiceBundle:Default:dashboard.html.twig', array(
                        'moneyInWallet' => $moneyInWallet,
                        'incomesCount' => $incomesCount,
                        'withdrawsCount' => $withdrawsCount,
                
                        'ticketsCount' => $data['ticketsCount'],
                        'notificationCount' => $data['notificationCount'],
                        'notes' => $data['notes'],
                        'tickets' => $data['tickets'],
                
                        'idUser' => $user->getIdUser(),
                        'chartForm' => $form->createView(),
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

        $incomes = null;
        $user = $this->getUser();
        $data = $this->getTicketsAndNotes($user);

        if ($user != null) {
            $wm = $this->get('walletManager');
            $incomes = $wm->loadWalletManagerFromDB($user)->getIncomes();
        }


        return $this->render('BiznesServiceBundle:Default:incomes.html.twig', array(
                    'incomes' => $incomes,
            
                    'ticketsCount' => $data['ticketsCount'],
                    'notificationCount' => $data['notificationCount'],
                    'notes' => $data['notes'],
                    'tickets' => $data['tickets'],
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
        $user = $this->getUser();
        $data = $this->getTicketsAndNotes($user);
        
        if ($user != null) {
            $wm = $this->get('walletManager');
            $expanses = $wm->loadWalletManagerFromDB($user)->getExpanses();

        }

        return $this->render('BiznesServiceBundle:Default:expanses.html.twig', array(
                    'expanses' => $expanses,
            
                    'ticketsCount' => $data['ticketsCount'],
                    'notificationCount' => $data['notificationCount'],
                    'notes' => $data['notes'],
                    'tickets' => $data['tickets'],
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
        $user = $this->getUser();
        $data = $this->getTicketsAndNotes($user);
        
        if ($user != null) {
            $wm = new WalletManager($em);
            $wm->countMoneyInWallet($user);
            $availableMoney = $wm->getMoneyInWallet();
        }

        $form = $this->createForm(ExpansesType::class, null, array(
            'attr' => array(
                'id' => 'withdrawForm',
                'class' => 'form',
            ),
        ));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if($form->get('value')->getData() > 1){
                $encoderService = $this->get('security.password_encoder');

                $password = $form->get('password')->getData();
                $match = $encoderService->isPasswordValid($user, $password);

                if($match) {
                    $expanse = $wm->addWithdraw($this->getUser(), $form, new \DateTime);
                    if ($expanse != null) {
                        return $this->redirectToRoute('withdrawRealized', array('id' => $expanse->getIdExpanse()));
                    }
                    else {
                        $msg = 'Wprowadzone dane były błedne lub nie posiadasz wystarczającej ilości pieniędzy.';
                    }
                }
                else{
                    $msg = 'Podane hasło jest nie właściwe.';
                }
            }
            else{
                $msg = 'Minimalna kwota jaką można wypłacić to 30 złotych.';
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
            
                    'ticketsCount' => $data['ticketsCount'],
                    'notificationCount' => $data['notificationCount'],
                    'notes' => $data['notes'],
                    'tickets' => $data['tickets'],
            
                    'msg' => $msg,
        ));
    }

    /**
     * @Route("/withdrawrealized/{id}", name="withdrawRealized", requirements={"id": "\d+"})
     */
    public function withdrawRealizationAction(Request $request, $id = null){
        if($id == null || !is_numeric($id)){
            throw new \Exception('Expanse id is null or not numeric.');
        }
        $user = $this->getUser();
        $data = $this->getTicketsAndNotes($user);

        $em = $this->getDoctrine()->getManager();
        $expanse = $em->getRepository('BiznesDatabaseBundle:Expanses')
            ->findOneBy(array(
                'idExpanse' => $id,
            ));

        $idUser = $expanse->getIdUser()->getIdUser();
        if($expanse == null || $idUser != $user->getIdUser()){
            throw new \Exception('There is no expanse with that id or you have not permission to see this.');
        }


        return $this->render('BiznesServiceBundle:Default:withdrawRealized.html.twig', array(
            'expanse' => $expanse,
            'ticketsCount' => $data['ticketsCount'],
            'notificationCount' => $data['notificationCount'],
            'notes' => $data['notes'],
            'tickets' => $data['tickets'],
        ));
    }

    /**
     * @Route("/contract/{id}", name="contract", requirements={"id": "\d+"})
     */
    public function contractAction($id = null){
        if($id == null || !is_numeric($id)){
            throw new \Exception('Expanse id is null or not numeric.');
        }

        $em = $this->getDoctrine()->getManager();
        $expanse = $em->getRepository('BiznesDatabaseBundle:Expanses')
            ->findOneBy(array(
                'idExpanse' => $id,
            ));

        $user = $this->getUser();
        if($user == null){
            throw new \Exception('User cant be null.');
        }

        $um = $this->get('userManager');
        $um->loadDataFromUser($user);
        $userData = $um->getUserData();
        $userAddress = $um->getUserAddress();

        $date = new \DateTime();
        $dateExecution = date_add($date, date_interval_create_from_date_string('1 days'));

        $snappy = $this->get('knp_snappy.pdf');
        $filename = $expanse->getContractNumber();

        $html = $this->renderView('BiznesServiceBundle:Default:contract.html.twig', array(
            'expanse' => $expanse,
            'user' => $user,
            'userData' => $userData,
            'userAddress' => $userAddress,
            'dateExecution' => $dateExecution,
            'contractNumber' => $filename,
        ));

        return new Response(
            $snappy->getOutputFromHtml($html), 200, array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="UMOWA/' . $filename . '.pdf"'
            )
        );
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


        $user = $this->getUser();
        $data = $this->getTicketsAndNotes($user);

        return $this->render('BiznesServiceBundle:Default:newTicket.html.twig', array(
                    'ticketForm' => $form->createView(),
            
                    'ticketsCount' => $data['ticketsCount'],
                    'notificationCount' => $data['notificationCount'],
                    'notes' => $data['notes'],
                    'tickets' => $data['tickets'],
        ));
    }

    /**
     * @Route("/support/messages", name="messages")
     */
    public function messagesAction() {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }

        $user = $this->getUser();
        $data = $this->getTicketsAndNotes($user);

        return $this->render('BiznesServiceBundle:Default:messages.html.twig', array(
                    'ticketsCount' => $data['ticketsCount'],
                    'notificationCount' => $data['notificationCount'],
                    'notes' => $data['notes'],
                    'tickets' => $data['tickets'],
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

        $ticket = null;
       
        $user = $this->getUser();
        $data = $this->getTicketsAndNotes($user);
        if ($user != null) {
            $tm = $this->get('ticketsManager');
            $ticket = $tm->loadByIdTicket($id)->getTicket();
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
        if ($form->isSubmitted() && $form->isValid()) {
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

                'ticketsCount' => $data['ticketsCount'],
                'notificationCount' => $data['notificationCount'],
                'notes' => $data['notes'],
                'tickets' => $data['tickets'],
        ));
    }

    /**
     * @Route("/notatifications", name="notatifications")
     */
    public function notatificationsAction() {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('You have to be fully authenticated user.');
        }
        $user = $this->getUser();
        $data = $this->getTicketsAndNotes($user);

        return $this->render('BiznesServiceBundle:Default:notifications.html.twig', array(
                'ticketsCount' => $data['ticketsCount'],
                'notificationCount' => $data['notificationCount'],
                'notes' => $data['notes'],
                'tickets' => $data['tickets'],
        ));
    }

    /**
     * @Route("/chart", name="getChart")
     * @Method({"POST"})
     */
    public function chartAction(Request $request) {

        $totalIncomesValue = null;
        $verifiedIncomesValue = null;
        $withdrawsValue = null;
        $incomesByMonths = array();

        if(true) {
            //$user = $this->getUser();
            $idUser = $request->request->get('idUser');
            $em = $this->getDoctrine()->getManager();
            $user = null;
            if (is_numeric($idUser)) {
                $user = $em->getRepository('BiznesDatabaseBundle:Users')
                        ->findOneBy(array('idUser' => $idUser));
                if ($user === null) {
                    exit;
                }
            }

            $wm = $this->get('walletManager');
            $wm->loadWalletManagerFromDB($user);

            $incomes = $wm->getIncomes();
            foreach ($incomes as $income) {
                $date = $income->getDateIncome();
                $month = $date->format('F');
                $month = strval($month);

                if (!isset($incomesByMonths[$month])) {
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
        } 
        else {
            throw new \Exception('Only POST method availiable.');
        }

        $data = array(
            'idUser' => $idUser,
            'totalIncomesValue' => $totalIncomesValue,
            'verifiedIncomesValue' => $verifiedIncomesValue,
            'withdrawsValue' => $withdrawsValue,
            'incomesByMonths' => $incomesByMonths,
        );

        $objJson = new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);
        return $objJson;
    }

    
    /**
     * @Route("/faq", name="faq")
     */
    public function faqAction(){
        $user = $this->getUser();
        $data = null;
        if($user !== null){
            $data = $this->getTicketsAndNotes($user);
        }
        
        
        return $this->render('BiznesServiceBundle:Default:faq.html.twig', array(
            'ticketsCount' => $data['ticketsCount'],
            'notificationCount' => $data['notificationCount'],
            'notes' => $data['notes'],
            'tickets' => $data['tickets'],
        ));
    }
}
