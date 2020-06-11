<?php

namespace App\Form;

use App\Entity\Plateau;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;



class PlateauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class)
            ->add('description',TextareaType::class,
                  ['help' => 'Maximum 350 caractères'],
                  ['attr' => ['maxlength' => 350]])
            ->add('nbPion', ChoiceType::class, ['choices'  => [
                                                            '1' => 1,
                                                            '2' => 2,
                                                            '3' => 3,
                                                            '4' => 4]])
            ->add('nbFaceDe', ChoiceType::class, ['choices'  => [
                                                            '1' => 1,
                                                            '2' => 2,
                                                            '3' => 3,
                                                            '4' => 4]])
            ->add('niveauDifficulte', ChoiceType::class, ['choices'  => [
                                                            'Facile' => 'Facile',
                                                            'Moyen' => 'Moyen',
                                                            'Difficile' => 'Difficile'
                                                        ]])
            ->add('cases', CollectionType::class,
                 ['entry_type' => CasesType::class,
                  'entry_options' => ['label' => false],
                  'allow_add' => true,
                  'allow_delete' => true,
                  'by_reference' => false,
                  'label'=> false,
                  'prototype' => true,
                  'attr'         => [
                  'class' => 'collection-cases',
               ],
                ])
              ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Plateau::class,
        ]);
    }

    public function getBlockPrefix()
      {
          return 'plateau';
      }
}
