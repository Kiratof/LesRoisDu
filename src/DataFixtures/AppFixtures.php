<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Plateau;
use App\Entity\PlateauEnJeu;
use App\Entity\Pion;
use App\Entity\Cases;
use App\Entity\Ressource;
use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Partie;
use Faker;

class AppFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $plateau12 = new Plateau();
        $plateau12->setNom("12 cases");
        $plateau12->setDescription($faker->realText($maxNbChars = 400, $indexSize = 2));
        $plateau12->setNiveauDifficulte("Facile");
        $plateau12->setNbCases(12);
        $plateau12->setCode("12DCE");
        $plateau12->setDerniereModification(new \Datetime('now'));

        $manager->persist($plateau12);


        $roles[] = 'ROLE_USER';
        $utilisateur1 = new Utilisateur();
        $utilisateur1->setPseudo("calbanel");

        $plainPassword = 'mcb';
        $utilisateur1->setMotDePasse($this->passwordEncoder->encodePassword(
            $utilisateur1,
            $plainPassword
        ));

        $utilisateur1->setEstInvite(false);
        $utilisateur1->setAvatar($faker->imageUrl($width = 200,$height = 200,'cats'));
        $utilisateur1->setEmail("clement.albanel@gmail.com");
        $utilisateur1->setRoles($roles);

        $manager->persist($utilisateur1);

        $plateauEnJeu = new PlateauEnJeu();
        $plateauEnJeu->setNom("12 cases");
        $plateauEnJeu->setDescription("Et merci bien");
        $plateauEnJeu->setNiveauDifficulte("Moyen");
        $plateauEnJeu->setNbCases(12);

        $partie = new Partie();
        $partie->setPlateau($plateau12);
        $partie->setPlateauDeJeu($plateauEnJeu);
        $partie->setCreateur($utilisateur1);
        $partie->setNom("Partie De Clém");
        $partie->setDescription("Partie carrément incroyable !");
        $partie->setCode("ABCDE");
        $partie->setNbPlateaux(1);
        $partie->setNbPionParPlateau(1);
        $partie->setNbFacesDe(4);
        $partie->setEstLance(false);
        $partie->setDerniereModification(new \Datetime('now'));


        $utilisateur1->addPartiesCree($partie);

        $plateauEnJeu->setPartie($partie);


        $utilisateur1->addPlateau($plateau12);
        $utilisateur1->addPlateauEnJeux($plateauEnJeu);

        $manager->persist($partie);

        $pion1 = new Pion();
        $pion1->setNom("clem");
        $pion1->setCouleur("12578");
        $pion1->setAvancementPlateau(2);
        $pion1->setPlateauEnJeu($plateauEnJeu);
        $pion1->setNumeroJoueur(1);

        $manager->persist($pion1);

        $plateauEnJeu->addPion($pion1);

       	$tabCases = array();
        for ($i=0; $i < 12; $i++) {
            $cases = new Cases();
            $cases->setDescriptifDefi($faker->realText($maxNbChars = 100, $indexSize = 2))
                ->setConsignes($faker->realText($maxNbChars = 400, $indexSize = 2))
                ->setCodeValidation($faker->randomNumber($nbDigits = 5, $strict = false))
                ->setPlateau($plateau12)
                ->setNumero($i+1);

            array_push($tabCases, $cases);
            $manager->persist($cases);
        }

        $manager->flush();
    }
}
