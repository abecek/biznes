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

use Biznes\DatabaseBundle\Entity\Ratings;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Description of RemindPassType
 *
 * @author Michal
 */
class RatingType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('value', ChoiceType::class, array(
                    'label' => 'Ocena:',
                    'choices' => array(
                        '5' => 5,
                        '4' => 4,
                        '3' => 3,
                        '2' => 2,
                        '1' => 1,  
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                        ),
                ))->add('text', TextareaType::class, array(
                    'label' => 'Komentarz:',
                    'attr' => array(
                        'class' => 'form-control',
                        ),                 
                ))->add('submit', SubmitType::class, array(
                    'label' => 'Zostaw ocenę',
                    'attr' => array(
                        'class' => 'btn btn-primary btn-lg btn-block',
                        ),
                ));
        
    }
    
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => Ratings::class,
        ));
    }
}
