<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Biznes\DatabaseBundle\Form;

use Biznes\DatabaseBundle\Entity\Withdraws;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


/**
 * Description of WithdrawType
 *
 * @author Michal
 */
class WithdrawsType extends AbstractType{
    //put your code here
    
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options) {
        $builder->add('value', MoneyType::class, array(
           'required' => true,
           'label' => 'Kwota:',
           'currency' => '',
           'attr' => array(
               'class' => 'form-control',
               'placeholder' => 'Wpisz kwotę'
               ),
        ))
            ->add('password',PasswordType::class, array(
                'label' => 'Hasło potwierdzające:',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('makeWithdraw', ButtonType::class, array(
                'label' => 'Zleć wypłate',
                'attr' => array(
                    'class' => 'btn btn-primary btn-lg btn-block',
                    'data-toggle' => 'modal',
                    'data-target' => '#confirmNewWithdraw',
                    ),
            ));
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => Withdraws::class,
        ));
    }
}
