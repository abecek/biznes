<?php

/*
 *  Michał Błaszczyk
 */
namespace Biznes\DatabaseBundle\Form;

use Biznes\DatabaseBundle\Entity\Users;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Description of RegisterType
 *
 * @author Michal
 */
class UsersType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('username', TextType::class)
                ->add('email', EmailType::class)
                
                //->add('password', PasswordType::class)
                
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options'  => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password'),
                ))
                        
                ->add('gender', ChoiceType::class, array(
                    'choices' => array(
                        'male' => 'M',
                        'female' => 'K'
                    ),
                    'placeholder' => 'Choice your gender',
                ))
                
                //->add('agree_checkbox', CheckboxType::class, array('mapped' => false))
                
                ->add('save', SubmitType::class);
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => Users::class,
        ));
    }
    
}
