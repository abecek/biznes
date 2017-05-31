<?php

/*
 *  Michał Błaszczyk
 */
namespace Biznes\DatabaseBundle\Form;

use Biznes\DatabaseBundle\Entity\UsersData;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Description of RegisterType
 *
 * @author Michal
 */
class UsersDataType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name1', TextType::class)
                ->add('name2', TextType::class)
                ->add('surname', TextType::class)
                ->add('identityNumber', TextType::class, array(
                    'required'   => false,
                ))
                ->add('telephone', TextType::class)
                        
                ->add('language', ChoiceType::class, array(
                    'choices' => array(
                        'Polish' => 'pl',
                        'English' => 'em'
                    ),
                    'placeholder' => 'Choice your language',
                ))
                
                ->add('save', SubmitType::class);
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => UsersData::class,
        ));
    }
    
}
