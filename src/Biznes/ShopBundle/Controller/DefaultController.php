<?php

namespace Biznes\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Filesystem\Filesystem;

use Biznes\DatabaseBundle\Form\RatingType;
use Biznes\DatabaseBundle\Entity\Ratings;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/shop")
 */
class DefaultController extends Controller {

    /**
     * @Route("/{referer}", name="shop", requirements={"referer": "\d+"})
     */
    public function indexAction($referer = null) {
        $session = new Session();

        if ($referer != null) {
            $session->set('referer', $referer);
        }

        $user = $this->getUser();
        $um = $this->get('userManager');
        $um->loadDataFromUser($user);

        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('BiznesDatabaseBundle:Products')
                ->findAll();

        $categories = $em->getRepository('BiznesDatabaseBundle:Categories')
                ->findAll();

        $cart = $this->get('cartManager');
        $cart->loadFromSession();

        return $this->render('BiznesShopBundle:Default:index.html.twig', array(
                    'products' => $products,
                    'cart' => $cart,
                    'categories' => $categories,
        ));
    }

    /**
     * @Route("/product/{id}/{referer}", name="product", requirements={"id": "\d+", "referer": "\d+"})
     */
    public function productAction(Request $request, $id, $referer = null) {
        $session = new Session();
        if ($referer != null) {
            $session->set('referer', $referer);
        }

        if (is_numeric($id)) {
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository('BiznesDatabaseBundle:Products')
                    ->findOneByIdProduct($id);

            $cart = $this->get('cartManager');
            $cart->loadFromSession();

            if ($product !== null){
                $userCanGiveRating = false;
                $isInCart = false;
                
                if($cart->isInCart($id)){
                   $isInCart = true; 
                }
                
                //ADDING RATINGS
                $um = $this->get('userManager');
                $user = $this->getUser();
                if($user !== null){
                    /* TO DO
                     * Zmienic/usunac ta glupia funkcje
                     * na zwykla true/false 
                     * canUserRateProduct(user, idProduct/Product)
                     * ktora bedzie sprawdzac czy jest juz taki rating
                     */
                    $productsAvailableToRate = $um->getProductsUserCanRate($user);

                    if(!empty($productsAvailableToRate)){
                        if(in_array($id, $productsAvailableToRate)){
                            $userCanGiveRating = true;
                        }
                    }
                }
                
                $rating = new Ratings();
                $form = $this->createForm(RatingType::class, null, array(
                    'method' => 'POST',
                    'attr' => array('class' => 'form'),
                ));
                        
                $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid()){
                    $rating->setIdUser($user);
                    $rating->setIdProduct($product);
                    $rating->setDateRating(new \DateTime);
                    $rating->setValue($form->get('value')->getData());
                    $rating->setText($form->get('text')->getData());
                    $em->persist($rating);
                    $em->flush();
                    
                    return $this->redirectToRoute('product', array(
                        'id' => $id,
                        'referer' => $referer,
                    ));
                }
                
                //Displaying Ratings and stars
                $rates = $em->getRepository('BiznesDatabaseBundle:Ratings')
                        ->findBy(array(
                            'idProduct' => $id,
                            'isAccepted' => '1',
                        ));
                
                if(!empty($rates)){
                    $productRate = 0.0;
                    $i=0;
                    foreach($rates as $rate){
                        $productRate += $rate->getValue();
                        $i++;
                    }
                    $productRate /= $i;
                }
                else{
                    $productRate = floatval($product->getRating());
                }
               
                $productRate = round($productRate, 1);
                $roundedRate = round($productRate);
                $starsOn = $roundedRate;
                $starsOff = null;
                if(5-$roundedRate >= 1){
                    $starsOff = 5-$roundedRate;
                }

                return $this->render('BiznesShopBundle:Default:product.html.twig', array(
                            'product' => $product,
                            'isInCart' => $isInCart,
                            'cart' => $cart,
                    
                            'productRate' => $productRate,
                            'starsOn' => $starsOn,
                            'starsOff' => $starsOff,
                            'userCanGiveRating' => $userCanGiveRating,
                            'rates' => $rates,
                            'ratingForm' => $form->createView(),
                ));
            }
            else{
                return $this->redirectToRoute('shop');
                //TO DO
//                $products = $em->getRepository('BiznesDatabaseBundle:Products')
//                ->findAll();
//                return $this->render('BiznesShopBundle:Default:index.html.twig', array(
//                            'products' => $products,
//                            'cart' => $cart,
//                ));
            }
        }
    }

    /**
     * @Route("/created", name="accountCreatedShop")
     */
    public function createdAction() {
        $cart = $this->get('cartManager');
        $cart->loadFromSession();

        return $this->render('BiznesShopBundle:Default:registerCreated.html.twig', array(
                    'cart' => $cart,
        ));
    }

    /**
     * @Route("/personalinfo", name="shopPersonalDataInfo")
     */
    public function personalInfoAction() {
        $cart = $this->get('cartManager');
        $cart->loadFromSession();

        return $this->render('BiznesShopBundle:Default:personalDataInfo.html.twig', array(
                    'cart' => $cart,
        ));
    }

    /**
     * @Route("/product/preview/{id}", name="preview", requirements={"id": "\d+"}))
     */
    public function previewAction($id = null){
        if(!is_numeric($id) || $id == null){
            return $this->redirectToRoute('shop');
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository('BiznesDatabaseBundle:Products')
                    ->findOneBy(array(
                        'idProduct' => $id,
                    ));
            if($product == null){
                return $this->redirectToRoute('shop');
            }
            
            $filename = $product->getFilename();
            
            $fs = new Filesystem();
            if(!$fs->exists('../src/Biznes/ShopBundle/Resources/views/Default/Products/' . $filename . '/index.html.twig')){
                throw new \Exception('There is no preview file for this product or filename is wrong');
            }
            
            return $this->render('BiznesShopBundle:Default/Products/' . $filename . ':index.html.twig', array(
                'id' => $id,
                'product' => $product,
                'filename' => $filename,
            ));               
        }
    }
}
