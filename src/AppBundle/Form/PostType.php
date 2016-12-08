<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Make some nice prompts for the post body textarea
        $prompts = array(
            "Tell us a story...",
            "What's on your mind?",
            "Come on! Share your knowledge...",
            "Let us know what you're thinking...",
            "Your audience are eager...",
            "What have you learnt..."
        );

        // Grab a prompt for our body placeholder
        $promptKey = array_rand($prompts);
        $prompt = $prompts[$promptKey];

        $builder
            ->add('title', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Another wonderful summers day',
                    'length' => 100
                ),
                'required' => true
            ))
            ->add('subtitle', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'The story of my weekend at the beach',
                    'length' => 150
                ),
                'required' => false
            ))
            ->add('slug', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'another-wonderful-summers-day',
                    'length' => 100
                ),
                'required' => false
            ))
            ->add('shortDescription', TextareaType::class, array(
                'attr'=> array(
                    'class' => 'materialize-textarea',
                    'placeholder' => 'Give us the short version here...',
                    'length' => 250
                ),
                'required' => true
            ))
            ->add('body', TextareaType::class, array(
                'attr'=> array(
                    'class' => 'materialize-textarea',
                    'placeholder' => $prompt
                ),
                'required' => true
            ))
            ->add('headerImage', FileType::class, array(
                'data_class' => null,
                'label' => 'Header Image',
                'required' => true
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Save',
                'attr' => array(
                    'class' => 'btn waves-effect waves-light'
                )
            )
        );
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
