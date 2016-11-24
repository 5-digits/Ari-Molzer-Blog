<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Another wonderful summers day',
                    'length' => 100
                )
            ))
            ->add('subtitle', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'The story of my weekend at the beach',
                    'length' => 150
                )
            ))
            ->add('shortDescription', TextType::class, array(
                'required' => true
            ))
            ->add('slug', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'another-wonderful-summers-day'
                )
            ))
            ->add('body', TextareaType::class, array(
                'attr'=> array(
                    'class' => 'materialize-textarea',
                    'placeholder' => 'Upload a post hero image'
                ),
                'required' => true
            ))
            ->add('headerImage', FileType::class, array(
                'label' => 'Header Image',
                'required' => true
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Save',
                'attr' => array(
                    'class' => 'btn waves-effect waves-light'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Post',
        ));
    }

    public function getName()
    {
        return 'app_bundle_blog_post';
    }
}
