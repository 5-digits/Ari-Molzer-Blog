<?php
/**
 * Created by PhpStorm.
 * User: arimolzer
 * Date: 16/11/2016
 * Time: 12:33 PM
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, array(
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Username or Email'
                )
            ))
            ->add('password', TextType::class, array(
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Password'
                )
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Login',
                'attr' => array(
                    'class' => 'btn waves-effect waves-light'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
        ));
    }

    public function getName()
    {
        return 'app_bundle_user';
    }

}