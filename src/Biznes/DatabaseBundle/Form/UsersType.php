<?php

/*
 *  Michał Błaszczyk
 */
namespace Biznes\DatabaseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Biznes\DatabaseBundle\Entity\Users;

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
        $builder->add('username', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Login:',
                ))
                ->add('email', EmailType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Email:',
                ))
                
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options'  => array(
                        'label' => 'Hasło:', 
                        'attr' => array('class' => 'form-control')
                    ),
                    'second_options' => array(
                        'label' => 'Powtórz hasło:', 
                        'attr' => array('class' => 'form-control')
                    ),
                )) 
                        
                ->add('gender', ChoiceType::class, array(
                    'label' => 'Płeć:',
                    'choices' => array(
                        'mężczyzna' => 'M',
                        'kobieta' => 'K'
                    ),
                    'placeholder' => 'Wybierz płeć',
                    'attr' => array('class' => 'form-control'),
                ))
                
                //->add('agree_checkbox', CheckboxType::class, array('mapped' => false))
                
                ->add('createAccount', SubmitType::class, array(
                    'label' => 'Załóż konto!',
                    'attr' => array(
                        'class' => 'btn btn-primary btn-lg btn-block',
                    ),
                ));
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => Users::class,
        ));
    }
    
}
