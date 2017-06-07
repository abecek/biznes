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
        $builder->add('country', TextType::class)
                ->add('city', TextType::class)
                ->add('postCode', TextType::class)
                ->add('street', TextType::class)
                ->add('nrHouse', TextType::class)
                ->add('nrFlat', TextType::class, array(
                    'required'   => false,
                ))  
                ->add('save', SubmitType::class);
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => UsersAddresses::class,
        ));
        $resolver->setRequired('userAddress');
    }
    
}
