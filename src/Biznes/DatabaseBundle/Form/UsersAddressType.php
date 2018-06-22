<?php

/*
 *  Michał Błaszczyk
 */
namespace Biznes\DatabaseBundle\Form;

use Biznes\DatabaseBundle\Entity\UsersAddresses;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Description of RegisterType
 *
 * @author Michal
 */
class UsersAddressType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('country', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Kraj'
                ))
                ->add('city', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Miasto'
                ))
                ->add('postCode', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Kod pocztowy'
                ))
                ->add('street', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Ulica'
                ))
                ->add('nrHouse', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Numer budynku'
                ))
                ->add('nrFlat', TextType::class, array(
                    'required'   => false,
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Numer mieszkania(opcjonalne)'
                ))  
                ->add('save', SubmitType::class, array(
                    'attr' => array(
                            'class' => 'btn btn-primary btn-block',
                        ),
                    'label' => 'Zapisz dane adresowe'
                ));
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => UsersAddresses::class,
        ));
        $resolver->setRequired('userAddress');
    }
    
}
