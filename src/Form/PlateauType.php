<?php

namespace App\Form;

use App\Entity\Plateau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PlateauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class, ['attr' => ['placeholder' => "Nom du plateau"]])
            ->add('description',TextType::class, ['attr' => ['placeholder' => "Description du plateau"]])
            ->add('niveauDifficulte', ChoiceType::class, ['choices'  => [
                                                            'Facile' => 'Facile',
                                                            'Moyen' => 'Moyen',
                                                            'Difficile' => 'Difficile'
                                                        ]])
            ->add('nbCases', ChoiceType::class, ['choices'  => [
                                                            ' '  => 1,
                                                            '12' => 12,
                                                            '13' => 13,
                                                            '14' => 14,
                                                            '15' => 15,
                                                            '16' => 16,
                                                            '17' => 17,
                                                            '18' => 18,
                                                            '19' => 19,
                                                            '20' => 20,
                                                            '21' => 21,
                                                            '22' => 22,
                                                            '22' => 22,
                                                            '23' => 23,
                                                            '24' => 24,
                                                            '25' => 25,
                                                            '26' => 26,
                                                            '27' => 27,
                                                            '28' => 28,
                                                            '29' => 29,
                                                            '30' => 30,
                                                            '31' => 31
                                                        ]])
            ->add('cases', CollectionType::class,
                 ['entry_type' => CasesType::class,
                  'entry_options' => ['label' => false],
                  'allow_add' => true,
                  'allow_delete' => true,
                  'by_reference' => false
                ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Plateau::class,
        ]);
    }
}
