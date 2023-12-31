<?php

namespace App\Form;

use App\Entity\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-input block border rounded bg-transparent w-full px-3 py-1 text-base font-normal outline-none mb-2',
                    'placeholder' => 'Entity name...',
                ],
                'label' => false,
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-input block border rounded bg-transparent w-full px-3 py-1 text-base font-normal outline-none mb-2',
                    'placeholder' => 'Description...',
                    'rows' => 5,
                ],
                'label' => false,
            ])
            ->add('images', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'attr'     => [
                    'accept' => 'image/*',
                    'multiple' => 'multiple',
                ],
                'label' => false,
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'text-white bg-blue-500 px-5 py-1.5 rounded font-medium text-base float-right'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entity::class,
        ]);
    }
}
