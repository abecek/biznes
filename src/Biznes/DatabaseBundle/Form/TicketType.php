<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Biznes\DatabaseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Biznes\DatabaseBundle\Entity\Tickets;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Description of TicketType
 *
 * @author Michal
 */
class TicketType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('title', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Tytuł:',
                ))
            ->add('text', TextareaType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Treść:',
                ))
            ->add('createTicket', SubmitType::class, array(
                    'label' => 'Załóż nowy wątek!',
                    'attr' => array(
                        'class' => 'btn btn-primary btn-block',
                    ),
                ));
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => Tickets::class,
        ));
    }
    
}
