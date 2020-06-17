<?php

namespace App\Form;

use App\Entity\Partie;
use App\Entity\Plateau;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\PlateauRepository;
use Symfony\Component\Security\Core\Security;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class PartieType extends AbstractType
{
  private $plateauRepository;
  private $security;

  public function __construct(Security $security, PlateauRepository $plateauRepository)
  {
    $this->plateauRepository = $plateauRepository;
    $this->security = $security;
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
    ->add('nom',TextType::class)
    ->add('description',TextareaType::class,[
      'help' => 'Maximum 350 caractères'
    ],
    [
      'attr' => ['maxlength' => 350]
    ])
    ->add('Valider', SubmitType::class);

    //Customizing the Form Based on the Underlying Data
    $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        $partie = $event->getData();
        $form = $event->getForm();

        // checks if the Partie object is "new"
        // If no data is passed to the form, the data is "null".
        // This should be considered a new "Partie"
        if (!$partie || null === $partie->getId()) {
            $form->add('plateau',EntityType::class, [
              'class' => Plateau::class,
              'choices' => $this->plateauRepository->findPlateauAvecCasesByUser($this->security->getUser()->getId()),
              'choice_label' => 'nom',
              'multiple' => true,
              'expanded' => true,
            ]);}
    });
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => Partie::class,
    ]);

  }
}
