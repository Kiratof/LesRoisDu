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
            ->add('description',TextType::class)
            ->add('nbPlateaux',IntegerType::class,['data' => '1', 'attr'=> ['readonly'=> true ]])
            ->add('plateau', EntityType::class, [   'class' => Plateau::class,
                                                    'choices' => $this->plateauRepository->findPlateauAvecCasesByUser($this->security->getUser()->getId()),
                                                    'choice_label' => 'nom',
                                                    'multiple' => false,
                                                    'expanded' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Partie::class,
        ]);

    }
}
