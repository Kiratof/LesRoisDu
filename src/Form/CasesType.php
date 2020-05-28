<?php

namespace App\Form;

use App\Entity\Cases;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class CasesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descriptifDefi',Textareatype::class, ['attr' => ['placeholder' => "Saisir un descriptif"], 'label' => false])
            //->add('consignes')
            //->add('codeValidation')
            //->add('numero')
            ->add('position', HiddenType::class, [
            'attr' => [
                'class' => 'position-case',
                      ],
              ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cases::class,
        ]);
    }

    public function getBlockPrefix()
  {
      return 'cases';
  }
}
