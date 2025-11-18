<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', ChoiceType::class, [
                'label' => 'Note',
                'choices' => [
                    '⭐ 1 - Très mauvais' => 1,
                    '⭐⭐ 2 - Mauvais' => 2,
                    '⭐⭐⭐ 3 - Moyen' => 3,
                    '⭐⭐⭐⭐ 4 - Bon' => 4,
                    '⭐⭐⭐⭐⭐ 5 - Excellent' => 5,
                ],
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new Range(['min' => 1, 'max' => 5])
                ],
                'help' => 'Évaluez votre expérience de 1 à 5 étoiles'
            ])
            ->add('auteur', TextType::class, [
                'label' => 'Votre nom (optionnel)',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: Marie Diop']
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Votre avis',
                'required' => false,
                'rows' => 5,
                'attr' => [
                    'placeholder' => 'Partagez votre expérience... (max 1000 caractères)',
                    'maxlength' => 1000
                ],
                'help' => 'Soyez constructif et respectueux'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
