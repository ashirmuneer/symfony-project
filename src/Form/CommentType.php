<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\Comment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentText',TextareaType::class,[
                'label'=>false,
                'required' => true,
                'attr' => ['class'=> 'form-control']
            ])
            ->add('status', ChoiceType::class, [
                'attr' => ['class'=>'form-control'],
                'choices' => [
                    'Active' => true,
                    'Inactive' => false,
                ],
                'placeholder' => 'Please select an option', // Optional
                'required' => true,
                'label' => false // Optional
            ])
            ->add('blog_id', EntityType::class, [
                'class' => Blog::class,
                'choice_label' => 'title', 
                'placeholder' => 'Please select an option',
                'required' => true,
                'label' => false,
                'attr' => ['class'=>'form-control']             
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
