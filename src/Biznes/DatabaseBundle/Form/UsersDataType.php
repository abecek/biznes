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
        $builder->add('name1', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Imię',
                ))
                ->add('name2', TextType::class, array(
                    'required'   => false,
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Drugie imię(opcjonalnie)',
                ))
                ->add('surname', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Nazwisko',
                ))
                ->add('identityNumber', TextType::class, array(
                    'required'   => false,
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Pesel(opcjonalnie)',
                ))
                ->add('telephone', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Numer telefonu',
                ))                   
                ->add('language', ChoiceType::class, array(
                    'choices' => array(
                        'Polski' => 'pl',
                        'Angielski' => 'en'
                    ),
                    'placeholder' => 'Wybierz język',
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Język',
                ))
                
                ->add('save', SubmitType::class, array(
                    'attr' => array('class' => 'btn btn-primary btn-block'),
                    'label' => 'Zapisz dane personalne',
                ));
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => UsersData::class,
        ));
        $resolver->setRequired('userData');
    }
    
}
