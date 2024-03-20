<?php

namespace App\Form;

use App\Entity\Blog;
use PHPUnit\TextUI\XmlConfiguration\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogFormUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'attr' => ['class'=> 'form-control','placeholder' => 'enter title'],
                'required' => false,
                'label' => false,                
            ])
            ->add('description',TextareaType::class,[
                'attr' => ['class'=> 'form-control','rows' => 6],            
                'required' => false,
                'label' => false
            ])
            ->add('imagePath',FileType::class,[
                'attr' => ['class'=> 'form-control'],
                'required' => false,
                'label' => false,
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
