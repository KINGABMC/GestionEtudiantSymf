<?php

namespace App\Form;

use App\Entity\Departement;
use App\Entity\Employe;
use Dom\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomComplet', null, [
                'label' => 'Nom et prénom de l\'employé',
                'attr' => [
                    'placeholder' => 'Entrez le nom complet...',
                ]
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Numéro de téléphone de l\'employé',
                'attr' => [
                    'placeholder' => 'Entrez le numéro de téléphone...',
                    'readonly' => false
                ]
            ])
            ->add('adresse', TextareaType::class, [
                'label' => 'Adresse de l\'employé',
                'attr' => [
                    'placeholder' => 'Entrez l\'adresse...',
                    'rows' => 4
                ],
            ])
            ->add('embaucheAt', null, [
                'widget' => 'single_text',
                'label' => 'Date d\'embauche',
            ])
            ->add('isArchived', ChoiceType::class, [
                'label' => 'Archiver cet employé ?',
                'choices'  => [
                    'non' => false,
                    'oui' => true,
                ],
                'expanded' => true,
            ])
            ->add('departement', EntityType::class, [
                'class' => Departement::class,
                'choice_label' => 'name',
                'data' => $options['departement_default'],
            ])
             ->add('pays', TextType::class, [
                'mapped' => false,
                'label' => 'Pays',
                'attr' => [
                    'placeholder' => 'Entrez le pays...',
                ],
            ])
              ->add('ville', TextType::class, [
                'mapped' => false,
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Entrez la ville...',
                ],
            ])
              ->add('rue', TextType::class, [
                'mapped' => false,
                'label' => 'Rue',
                'attr' => [
                    'placeholder' => 'Entrez la rue...',
                ],
            ])
            ->add('photoFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'accept' => 'image/jpg,image/jpeg,image/png',
                ],
            ])
 
             ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer l\'employé',
                'attr' => [
                    'class' => 'btn btn-primary float-end w-50'
                 
                ]
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
            "attr" => ["data-turbo" => "false"],
            "departement_default" => null
        ]);
    }
}
