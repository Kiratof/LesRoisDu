<?php

namespace App\Form;

use App\Entity\Cases;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CasesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descriptifDefi',TextType::class, ['attr' => ['placeholder' => "Saisir un descriptif"]])
            //->add('consignes')
            //->add('codeValidation')
            //->add('numero')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cases::class,
        ]);
    }
}
