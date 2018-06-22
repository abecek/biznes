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

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Description of RemindPassType
 *
 * @author Michal
 */
class ResendActivLinkType extends AbstractType {
    //put your code herepublic function buildForm(FormBuilderInterface $builder, array $options){
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('emailOrUsername', TextType::class, array(
                    'label' => 'Adres Email lub Login:',
                    'attr' => array(
                        'class' => 'form-control'
                        ),
                ))->add('submit', SubmitType::class, array(
                    'label' => 'Wyślij ponownie link aktywacyjny!',
                    'attr' => array(
                        'class' => 'btn btn-primary btn-lg btn-block',
                        ),
                ));
        
    }
    
    /*
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => Users::class,
        ));
    }
    */
}
