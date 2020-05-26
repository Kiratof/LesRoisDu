<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Plateau;
use App\Entity\PlateauEnJeu;
use App\Entity\Pion;
use App\Entity\Cases;
use App\Entity\Ressource;
use App\Entity\Utilisateur;
use App\Entity\Partie;
use App\Repository\PlateauRepository;
use App\Repository\PlateauEnJeuRepository;
use App\Repository\PionRepository;
use App\Repository\CasesRepository;
use App\Repository\RessourceRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\PartieRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\UtilisateurType;
use App\Form\PartieType;
use App\Form\PlateauType;
use App\Form\CasesType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Security\LoginAuthenticator;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LesRoisDuController extends AbstractController
{
    private $passwordEncoder;


    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;

    }

    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        return $this->render('les_rois_du/index.html.twig');
    }

    /**
     * @Route("/conditions-utilisation", name="CGU")
     */
    public function CGU()
    {
        return $this->render('les_rois_du/CGU.html.twig');
    }

    /**
     * @Route("/but-application", name="ButApplication")
     */
    public function ButApplication()
    {
        return $this->render('les_rois_du/ButApplication.html.twig');
    }

     /**
     * @Route("/credits", name="credits")
     */
    public function affichageCredits()
    {
        return $this->render('les_rois_du/credits.html.twig');
    }

    /**
    * @Route("/aide", name="aides")
    */
   public function affichageAides()
   {
       return $this->render('les_rois_du/aides.html.twig');
   }

   /**
    * @Route("/mentions-legales", name="mentions_legales")
    */
   public function affichageMentionsLegales()
   {
       return $this->render('les_rois_du/mentionslegales.html.twig');
   }

    /**
     * @Route("/inscription", name="page_inscription")
     */
    public function affichagePageInscription(Request $request, ObjectManager $manager, GuardAuthenticatorHandler $guardHandler, LoginAuthenticator $authenticator)
    {
        // Création d'un utilisateur vierge
        $utilisateur=new Utilisateur();

        // Création de l'objet formulaire à partir du formulaire externalisé "UtilisateurType"
        $formulaireUtilisateur=$this->createForm(UtilisateurType::class, $utilisateur);

         /* On demande au formulaire d'analyser la dernière requête Http. Si le tableau POST contenu
        dans cette requête contient des variables pseudo,email etc. alors la méthode handleRequest()
        récupère les valeurs de ces variables et les affecte à l'objet $utilisateur */
        $formulaireUtilisateur->handleRequest($request);

        if ($formulaireUtilisateur->isSubmitted() && $formulaireUtilisateur->isValid())
        {
           // Avatar par défaut
            $utilisateur->setAvatar("/img/avatar8.jpg");

            // L'utilisateur a le role USER
            $roles[] =  'ROLE_USER';
            $utilisateur->setRoles($roles);

            // L'utilisateur crée un compte il n'est donc pas invité
            $utilisateur->setEstInvite(false);

            // Hashage du mot de passe
            $plainPassword = $utilisateur->getPlainPassword();
            $utilisateur->setMotDePasse($this->passwordEncoder->encodePassword(
                $utilisateur,
                $plainPassword
             ));

            $repositoryPlateau=$this->getDoctrine()->getRepository(Plateau::class);

                $plateauOriginel = $repositoryPlateau->find(2);

                $plateau = new Plateau();

                $date = New \DateTime();
                $plateau->setDerniereModification($date);

                $plateau->setNom($plateauOriginel->getNom());
                $plateau->setDescription($plateauOriginel->getDescription());
                $plateau->setNiveauDifficulte($plateauOriginel->getNiveauDifficulte());
                $plateau->setNbCases($plateauOriginel->getNbCases());
                $code = strtoupper(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 5, 5));
                $plateau->setCode($code);

                $tabCase = $plateauOriginel->getCases();
                foreach($tabCase as $uneCase){
                    $cases = new Cases();
                    $cases->setDescriptifDefi($uneCase->getDescriptifDefi())
                        ->setConsignes($uneCase->getConsignes())
                        ->setCodeValidation($uneCase->getCodeValidation())
                        ->setNumero($uneCase->getNumero())
                    ;

                    $cases->setPlateau($plateau);

                    $manager->persist($cases);

                    // On récupère les ressources des cases du plateau et les copie une par une dans les cases du plateau
                    $tabRessource = $uneCase->getRessources();
                    foreach($tabRessource as $uneRessource){
                        $ressource = new Ressource();

                        $ressource->setChemin($uneRessource->getChemin());


                        $ressource->setCases($cases);
                        $cases->addRessource($ressource);
                        $manager->persist($cases);

                        $manager->persist($ressource);

                    }
                }

                $utilisateur->addPlateau($plateau);

                $manager->persist($plateau);


            // Enregistrer l'utilisateur en base de données
           $manager->persist($utilisateur);
           $manager->flush();

           // Rediriger l'utilisateur vers la page d'accueil
           return $guardHandler->authenticateUserAndHandleSuccess(
                $utilisateur,
                $request,
                $authenticator,
                'main' // Nom du parefeu dans security.yaml
            );
        }

        return $this->render('les_rois_du/inscription.html.twig',['vueFormulaireInscription' => $formulaireUtilisateur->createView()]);
    }

    /**
     * @Route("/hub", name="hub")
     */
    public function affichageHub(UserInterface $user)
    {

        return $this->render('les_rois_du/hub.html.twig', ['utilisateur'=>$user]);
    }

    /**
     * @Route("/parties", name="espace_partie")
     */
    public function affichageEspacePartie(UserInterface $user, PartieRepository $partieRepository)
    {

        $cree = $partieRepository->findPartieByCreateur($user);

        $rejoins = $partieRepository->findPartieByJoueur($user);

        return $this->render('les_rois_du/espacepartie.html.twig', ['partiesCree'=>$cree, 'partiesRejoins'=>$rejoins, 'utilisateur'=>$user]);
    }

    /**
     * @Route("/compte/parties/tableau-de-bord", name="tableau_de_bord")
     */
    public function affichageTableauDeBordPartie(UserInterface $user, PartieRepository $repositoryPartie)
    {

        $cree = $repositoryPartie->findPartieByCreateur($user);

        return $this->render('les_rois_du/tableaudebord.html.twig', ['parties'=>$cree, 'utilisateur'=>$user]);
    }

    /**
     * @Route("/compte", name="espace_compte")
     */
    public function affichageEspaceCompte(UserInterface $user)
    {
        return $this->render('les_rois_du/espacecompte.html.twig', ['utilisateur'=>$user]);
    }


    /**
     * @Route("/plateaux", name="espace_plateau")
     */
    public function affichageEspacePlateau(UserInterface $user, PlateauRepository $repositoryPlateau)
    {

        $plateaux = $repositoryPlateau->findPlateauByUser($user);

        return $this->render('les_rois_du/espaceplateau.html.twig', ['plateaux'=>$plateaux, 'utilisateur'=>$user]);
    }

    /**
     * @Route("/creation/parties", name="creation_partie")
     */
    public function affichageCreationPartie(Request $request, ObjectManager $manager, UserInterface $user)
    {

       // Création d'une partie vierge
       $partie=new Partie();

       // Création de l'objet formulaire à partir du formulaire externalisé "PartieType"
       $formulairePartie = $this->createForm(PartieType::class, $partie);

       $formulairePartie->handleRequest($request);

       if ($formulairePartie->isSubmitted() && $formulairePartie->isValid())
       {

        $plateau = $partie->getPlateau();

        $date = New \DateTime();
        $partie->setDerniereModification($date);

        // On copie le plateau sélectionné dans le plateauEnJeu de la partie
        $plateauEnJeu = new PlateauEnJeu();
        $plateauEnJeu->setNom($plateau->getNom());
        $plateauEnJeu->setDescription($plateau->getDescription());
        $plateauEnJeu->setNiveauDifficulte($plateau->getNiveauDifficulte());
        $plateauEnJeu->setNbCases($plateau->getNbCases());

        $donneesPions = [['numero' => 1, 'nom' => "vert", 'couleur' => "green"], ['numero' => 2, 'nom' => "rouge", 'couleur' => "red"], ['numero' => 3, 'nom' => "jaune", 'couleur' => "yellow"], ['numero' => 4, 'nom' => "bleu", 'couleur' => "blue"]];

        for ($i=0; $i < 4; $i++) {

            $pion = new Pion();
            $pion->setNumeroJoueur($donneesPions[$i]["numero"]);
            $pion->setNom($donneesPions[$i]["nom"]);
            $pion->setCouleur($donneesPions[$i]["couleur"]);
            $pion->setAvancementPlateau(0);
            $pion->setPlateauEnJeu($plateauEnJeu);

            $manager->persist($pion);
        }

        $partie->setPlateauDeJeu($plateauEnJeu);
        $partie->setCreateur($user);

        $code = strtoupper(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 5, 5));
        $partie->setCode($code);
        $partie->setEstLance(false);

        $user->addPartiesCree($partie);

        $manager->persist($partie);

        $plateauEnJeu->setPartie($partie);

        $manager->persist($user);

        // On récupère les cases du plateau et les copie une par une dans le plateauEnJeu
        $tabCase = $plateau->getCases();
        foreach($tabCase as $uneCase){
            $cases = new Cases();
            $cases->setDescriptifDefi($uneCase->getDescriptifDefi())
                ->setConsignes($uneCase->getConsignes())
                ->setCodeValidation($uneCase->getCodeValidation())
                ->setNumero($uneCase->getNumero())
            ;

            $cases->setPlateauEnJeu($plateauEnJeu);

            $manager->persist($cases);

            // On récupère les ressources des cases du plateau et les copie une par une dans les cases du plateauEnJeu
            $tabRessource = $uneCase->getRessources();
            foreach($tabRessource as $uneRessource){
                $ressource = new Ressource();

                $ressource->setChemin($uneRessource->getChemin());


                $ressource->setCases($cases);
                $cases->addRessource($ressource);
                $manager->persist($cases);

                $manager->persist($ressource);

            }
        }

            $manager->persist($plateauEnJeu);

          // Enregistrer en base de données
          $manager->flush();

          $this->addFlash('success', 'La partie a été créée.');

          // Rediriger l'utilisateur vers la page d'accueil
          return $this->redirectToRoute('espace_partie');
       }
        return $this->render('les_rois_du/creationpartie.html.twig', ['vueFormulaireCreationPartie'=>$formulairePartie->createview(), 'action' => 'creer', 'partie' => null
        ]);
    }

    /**
     * @Route("/creation/modification/parties/{idPartie}", name="modification_parties")
     */
    public function affichageModificationPartie(Request $request, ObjectManager $manager, UserInterface $user, PartieRepository $partieRepository, $idPartie)
    {

       // Création d'une partie vierge
       $partie=$partieRepository->find($idPartie);

       if(in_array($partie, $user->getPartiesCree()->toArray())){
           // Création de l'objet formulaire à partir du formulaire externalisé "PartieType"
           $formulairePartie = $this->createFormBuilder($partie)
           ->add('nom',TextType::class, ['attr' => ['placeholder' => "Nom de la partie (Il apparaîtra dans l'espace partie des joueurs, veuillez faire attention à ce que vous saisissez)."]])
                ->add('description',TextType::class, ['attr' => ['placeholder' => "Description de la partie (Elle apparaîtra dans l'espace partie des joueurs, veuillez faire attention à ce que vous saisissez)."]])
                ->add('nbPlateaux',IntegerType::class,['data' => '1', 'attr'=> ['readonly'=> true ]])
                ->add('nbPionParPlateau', ChoiceType::class, ['choices'  => [
                                                                '1' => 1,
                                                                '2' => 2,
                                                                '3' => 3,
                                                                '4' => 4]])
                ->add('nbFacesDe', ChoiceType::class, ['choices'  => [
                                                                '1' => 1,
                                                                '2' => 2,
                                                                '3' => 3,
                                                                '4' => 4]])
                ->getForm();

           $formulairePartie->handleRequest($request);

           if ($formulairePartie->isSubmitted() && $formulairePartie->isValid())
           {
                $date = New \DateTime();
                $partie->setDerniereModification($date);

                $manager->persist($partie);

              // Enregistrer en base de données
              $manager->flush();

              $this->addFlash('success', 'La partie a été modifiée.');

              // Rediriger l'utilisateur vers la page d'accueil
              return $this->redirectToRoute('partie_en_cours', ['idPartie' => $idPartie]);
           }
            return $this->render('les_rois_du/creationpartie.html.twig', ['vueFormulaireCreationPartie'=>$formulairePartie->createview(), 'action' => 'modifier', 'partie' => $partie
            ]);
            }
        else{
            return $this->redirectToRoute('espace_partie');
        }
    }

    /**
     * @Route("/creation/plateaux", name="creation_plateau")
     */
    public function affichageCreationPlateau(Request $request, ObjectManager $manager, UserInterface $user)
    {

       // Création d'un plateau vierge
       $plateau=new Plateau();

       $case1 = new Cases();
       $case1 -> setDescriptifDefi('description1');
       $plateau->getCases()->add($case1);
       $case2 = new Cases();
       $case2 -> setDescriptifDefi('description2');
       $plateau->getCases()->add($case2);
       $case3 = new Cases();
       $case3 -> setDescriptifDefi('description3');
       $plateau->getCases()->add($case3);


       // Création de l'objet formulaire à partir du formulaire externalisé "PartieType"
       $formulairePlateau = $this->createForm(PlateauType::class, $plateau);

       $formulairePlateau->handleRequest($request);

       if ($formulairePlateau->isSubmitted() && $formulairePlateau->isValid())
       {


        $date = New \DateTime();
        $plateau->setDerniereModification($date);

        $code = strtoupper(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 5, 5));
        $plateau->setCode($code);

        $plateau->addUtilisateur($user);
        $manager->persist($plateau);

          // Enregistrer en base de données
          $manager->flush();

          $this->addFlash('success', 'Le plateau a été créé, vous pouvez désormais aller saisir les défis.');

          // Rediriger l'utilisateur vers la page d'accueil
          return $this->redirectToRoute('espace_plateau');
       }
        return $this->render('les_rois_du/creationplateau.html.twig', ['vueFormulaireCreationPlateau'=>$formulairePlateau->createview(), 'action' => 'creer', 'plateau' => null
        ]);
    }

    /**
     * @Route("/creation/modification/plateaux/{idPlateau}", name="modification_plateau")
     */
    public function affichageModificationPlateau(Request $request, ObjectManager $manager, UserInterface $user, PlateauRepository $plateauRepository, $idPlateau)
    {

       // Création d'une partie vierge
       $plateau = $plateauRepository->find($idPlateau);

       if(in_array($plateau, $user->getPlateaux()->toArray())){

           // Création de l'objet formulaire à partir du formulaire externalisé "PartieType"
           $formulairePlateau = $this->createFormBuilder($plateau)
                                ->add('nom',TextType::class, ['attr' => ['placeholder' => "Nom du plateau (Il apparaîtra dans l'espace plateau des utilisateurs à qui vous partagerai le plateau, veuillez faire attention à ce que vous saisissez)."]])
                                ->add('description',TextType::class, ['attr' => ['placeholder' => "Description du plateau (Elle apparaîtra dans l'espace plateau des utilisateurs à qui vous partagerai le plateau, veuillez faire attention à ce que vous saisissez)."]])
                                ->add('niveauDifficulte', ChoiceType::class, ['choices'  => [
                                                                'Facile' => 'Facile',
                                                                'Moyen' => 'Moyen',
                                                                'Difficile' => 'Difficile'
                                                            ]])
                                ->getForm();

           $formulairePlateau->handleRequest($request);

           if ($formulairePlateau->isSubmitted() && $formulairePlateau->isValid())
           {

                $date = New \DateTime();
                $plateau->setDerniereModification($date);

                $manager->persist($plateau);
              // Enregistrer en base de données
              $manager->flush();

              $this->addFlash('success', 'Le plateau a été modifié.');

              // Rediriger l'utilisateur vers la page d'accueil
              return $this->redirectToRoute('plateau', ['idPlateau' => $plateau->getId()]);
           }
            return $this->render('les_rois_du/creationplateau.html.twig', ['vueFormulaireCreationPlateau'=>$formulairePlateau->createview(), 'action' => 'modifier', 'plateau' => $plateau
            ]);
        }
        else{
            return $this->redirectToRoute('espace_plateau');
        }
    }

    /**
     * @Route("/creation/cases/{idPlateau}", name="creation_cases")
     */
    public function affichageCreationCases(Request $request, ObjectManager $manager, UserInterface $user, PlateauRepository $repositoryPlateau, $idPlateau)
    {

        $plateau = $repositoryPlateau->find($idPlateau);

        if(in_array($plateau, $user->getPlateaux()->toArray()) && $plateau->getCases()->isEmpty()){

            if ($request->getMethod() == 'POST') {

                $date = New \DateTime();
                $plateau->setDerniereModification($date);

                for ($i=1; $i <= $plateau->getNbCases(); $i++) {

                    $cases = new Cases();

                    $descriptif = $request->request->get('descriptif'.$i);

                    $cases->setDescriptifDefi($descriptif);

                    $cases->setNumero($i);

                    $cases->setPlateau($plateau);

                    $manager->persist($cases);


                }

                $manager->persist($plateau);

                $manager->flush();

                $this->addFlash('success', 'Les défis ont bien été saisis, vous pouvez désormais consulter votre plateau.');

                // Rediriger l'utilisateur vers la page d'accueil
                return $this->redirectToRoute('espace_plateau');

            }


            return $this->render('les_rois_du/creationcases.html.twig', ['plateau' => $plateau, 'cases' => null]);
        }
        else{
            return $this->redirectToRoute('espace_plateau');
        }


    }

    /**
     * @Route("/creation/modification/cases/{idPlateau}", name="modification_cases")
     */
    public function affichageModificationCases(Request $request, ObjectManager $manager, UserInterface $user, PlateauRepository $repositoryPlateau, CasesRepository $repositoryCases, $idPlateau)
    {

        $plateau = $repositoryPlateau->find($idPlateau);

        if(in_array($plateau, $user->getPlateaux()->toArray()) && !$plateau->getCases()->isEmpty()){

            if ($request->getMethod() == 'POST') {

                $date = New \DateTime();
                $plateau->setDerniereModification($date);

                for ($i=1; $i <= $plateau->getNbCases(); $i++) {

                    $cases = $repositoryCases->findOneBy(['plateau' => $plateau, 'numero' => $i]);

                    $descriptif = $request->request->get('descriptif'.$i);

                    $cases->setDescriptifDefi($descriptif);

                    $manager->persist($cases);

                }

                $manager->persist($plateau);

                $manager->flush();

                $this->addFlash('success', 'Les défis ont bien été modifiés.');

                // Rediriger l'utilisateur vers la page d'accueil
                return $this->redirectToRoute('plateau', ['idPlateau' => $plateau->getId()]);

            }


            return $this->render('les_rois_du/creationcases.html.twig', ['plateau' => $plateau, 'cases' => $plateau->getCases()]);
        }
        else{
            return $this->redirectToRoute('espace_plateau');
        }


    }

    /**
     * @Route("/parties/join{code}", name="join_partie")
     */
    public function joinPartie(ObjectManager $manager, UserInterface $user, $code, PartieRepository $repositoryPartie)
    {

        $partie = $repositoryPartie->findOneBy(['code' => $code]);



        if(!is_null($partie)){
            $plateauDeJeu = $partie->getPlateauDeJeu();
            if($partie->getJoueurs()->isEmpty()){

                $date = new \DateTime();
                $partie->setDateRejoins($date);

                $user->addPartiesRejoin($partie);
                $user->addPlateauEnJeux($partie->getPlateauDeJeu());

                $plateauDeJeu->setJoueur($user);

                // Enregistrer la ressource en base de données
                $manager->persist($plateauDeJeu);
                $manager->persist($partie);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success', 'Vous avez rejoins la partie.');

            }
            else
            {
                $this->addFlash('echec', 'Vous ne pouvez pas rejoindre cette partie car le nombre maximum de joueur a été atteint !');
            }
        }
        else
        {
            $this->addFlash('echec', "La partie n'existe pas !");

        }

        return $this->redirectToRoute('espace_partie');

    }

    /**
     * @Route("/plateaux/ajouter{code}", name="ajouter_plateau")
     */
    public function ajouterPlateau(ObjectManager $manager, UserInterface $user, $code, PlateauRepository $repositoryPlateau)
    {

        $plateauOriginel = $repositoryPlateau->findOneBy(['code' => $code]);

        if(!is_null($plateauOriginel)){

            if(!in_array($plateauOriginel,$user->getPlateaux()->toArray())){

                if(!$plateauOriginel->getCases()->isEmpty()){

                    $plateau = new Plateau();

                    $date = New \DateTime();
                    $plateau->setDerniereModification($date);

                    $plateau->setNom($plateauOriginel->getNom());
                    $plateau->setDescription($plateauOriginel->getDescription());
                    $plateau->setNiveauDifficulte($plateauOriginel->getNiveauDifficulte());
                    $plateau->setNbCases($plateauOriginel->getNbCases());
                    $code = strtoupper(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 5, 5));
                    $plateau->setCode($code);

                    $tabCase = $plateauOriginel->getCases();
                    foreach($tabCase as $uneCase){
                        $cases = new Cases();
                        $cases->setDescriptifDefi($uneCase->getDescriptifDefi())
                            ->setConsignes($uneCase->getConsignes())
                            ->setCodeValidation($uneCase->getCodeValidation())
                            ->setNumero($uneCase->getNumero())
                        ;

                        $cases->setPlateau($plateau);

                        $manager->persist($cases);

                        // On récupère les ressources des cases du plateau et les copie une par une dans les cases du plateau
                        $tabRessource = $uneCase->getRessources();
                        foreach($tabRessource as $uneRessource){
                            $ressource = new Ressource();

                            $ressource->setChemin($uneRessource->getChemin());


                            $ressource->setCases($cases);
                            $cases->addRessource($ressource);
                            $manager->persist($cases);

                            $manager->persist($ressource);

                        }
                    }

                    $user->addPlateau($plateau);

                    $manager->persist($plateau);
                    $manager->persist($user);
                    $manager->flush();
                    $this->addFlash('success', 'Vous avez ajouté le plateau à votre espace plateaux.');

                }

            }
            else
            {
                $this->addFlash('echec', 'Plateau déjà possédé !');
            }
        }
        else
        {
            $this->addFlash('echec', "Le plateau n'existe pas !");

        }

        return $this->redirectToRoute('espace_plateau');

    }


    /**
     * @Route("/plateaux/dupliquer{idPlateau}", name="duplication_plateau")
     */
    public function dupliquerPlateau(ObjectManager $manager, UserInterface $user, $idPlateau, PlateauRepository $repositoryPlateau)
    {

        $plateauOriginel = $repositoryPlateau->find($idPlateau);


        if(in_array($plateauOriginel,$user->getPlateaux()->toArray())){

            if(!$plateauOriginel->getCases()->isEmpty()){

                $plateau = new Plateau();

                $date = New \DateTime();
                $plateau->setDerniereModification($date);

                $plateau->setNom($plateauOriginel->getNom()." (Copie)");
                $plateau->setDescription($plateauOriginel->getDescription());
                $plateau->setNiveauDifficulte($plateauOriginel->getNiveauDifficulte());
                $plateau->setNbCases($plateauOriginel->getNbCases());
                $code = strtoupper(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 5, 5));
                $plateau->setCode($code);

                $tabCase = $plateauOriginel->getCases();
                foreach($tabCase as $uneCase){
                    $cases = new Cases();
                    $cases->setDescriptifDefi($uneCase->getDescriptifDefi())
                        ->setConsignes($uneCase->getConsignes())
                        ->setCodeValidation($uneCase->getCodeValidation())
                        ->setNumero($uneCase->getNumero())
                    ;

                    $cases->setPlateau($plateau);

                    $manager->persist($cases);

                    // On récupère les ressources des cases du plateau et les copie une par une dans les cases du plateau
                    $tabRessource = $uneCase->getRessources();
                    foreach($tabRessource as $uneRessource){
                        $ressource = new Ressource();

                        $ressource->setChemin($uneRessource->getChemin());


                        $ressource->setCases($cases);
                        $cases->addRessource($ressource);
                        $manager->persist($cases);

                        $manager->persist($ressource);

                    }
                }

                $user->addPlateau($plateau);

                $manager->persist($plateau);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success', 'Plateau dupliqué.');

            }
        }

            return $this->redirectToRoute('espace_plateau');

    }


    /**
     * @Route("/parties/{idPartie}", name="partie_en_cours")
     */
    public function affichagePartieEnCours($idPartie, UserInterface $user, PartieRepository $repositoryPartie)
    {

        $partie = $repositoryPartie->find($idPartie);
        // On récupère les parties créées par l'utilisateur
        $cree = $repositoryPartie->findPartieByCreateur($user);

        // On récupère les parties rjointes par l'utilisateur
        $rejoins = $repositoryPartie->findPartieByJoueur($user);

        // La partie n'est pas une des parties rejointes ou créées par l'utilisateur
        $trouve = false;

        foreach($cree as $unePartie){
            if($unePartie->getId() == $partie->getId()){
                $trouve = true; // La partie est l'une des parties créées par l'utilisateur
            }
        }

        foreach($rejoins as $unePartie){
            if($unePartie->getId() == $partie->getId()){
                $trouve = true; // La partie est l'une des parties rejointes par l'utilisateur
            }
        }

        // Si l'utilisateur a créé ou rejoint la partie il peut la voir
        if ($trouve == true)
        {
            return $this->render('les_rois_du/partieencours.html.twig',['partie'=>$partie,'partiesCree'=>$cree, 'partiesRejoins'=>$rejoins, 'utilisateur'=>$user]);
        }
        else // Sinon il est redirigé sur l'espace des parties
        {
            return $this->redirectToRoute('espace_partie');
        }
    }

    /**
     * @Route("/parametres/parties/{idPartie}", name="parametres_partie")
     */
    public function affichageParametresPartie($idPartie, UserInterface $user, PartieRepository $repositoryPartie)
    {

        $partie = $repositoryPartie->find($idPartie);

        // Si l'utilsateur est le créateur de la partie, il peut voir ses paramètres
        if ($partie->getCreateur()->getPseudo() == $user->getPseudo()){

            return $this->render('les_rois_du/parametrespartie.html.twig',['partie'=>$partie]);

        }
        else
        {
            return $this->redirectToRoute('espace_partie');
        }


    }

    /**
     * @Route("/plateaux/{idPlateau}", name="plateau")
     */
    public function affichagePlateau($idPlateau, UserInterface $user, PlateauRepository $repositoryPlateau)
    {

        $plateau = $repositoryPlateau->find($idPlateau);

        $plateaux = $repositoryPlateau->findPlateauByUser($user);

        if(in_array($plateau,$user->getPlateaux()->toArray()) && !$plateau->getCases()->isEmpty()){

            return $this->render('les_rois_du/plateau.html.twig',['plateau'=>$plateau, 'utilisateur'=>$user, 'plateaux' =>$plateaux]);
        }
        else{
            return $this->redirectToRoute('espace_plateau');
        }
    }

    /**
     * @Route("/parametres/plateaux/{idPlateau}", name="parametres_plateau")
     */
    public function affichageParametresPlateau($idPlateau, UserInterface $user, PlateauRepository $repositoryPlateau)
    {

        $plateau = $repositoryPlateau->find($idPlateau);

        if(in_array($plateau,$user->getPlateaux()->toArray()) && !$plateau->getCases()->isEmpty()){
            return $this->render('les_rois_du/parametresplateau.html.twig',['plateau'=>$plateau]);
        }
        else{
            return $this->redirectToRoute('espace_plateau');
        }
    }

    /**
     * @Route("/supression/parties/{idPartie}", name="supprimer_partie")
     */
    public function supprimerUnePartie($idPartie, UserInterface $utilisateur, PartieRepository $repositoryPartie)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $partie = $repositoryPartie->find($idPartie);

        if ($partie->getCreateur()->getPseudo() == $utilisateur->getPseudo()){ // Seul le créateur peut supprimer sa partie

            $plateauEnJeu = $partie->getPlateauDeJeu();

            $tabCase = $plateauEnJeu->getCases();
            foreach($tabCase as $uneCase){ // On enlève les cases une par une

                $tabRessource = $uneCase->getRessources();
                foreach($tabRessource as $uneRessource){ // Pour chaque case on enlève les ressources une par une

                    $entityManager->remove($uneRessource);

                }

                $entityManager->remove($uneCase);
            }

            $tabPion = $plateauEnJeu->getPions();
            foreach($tabPion as $unPion){ // On enlève chaque pion un par un

                $entityManager->remove($unPion);
            }

            $entityManager->remove($plateauEnJeu); // On supprime le plateauEnJeu

            $plateau = $partie->getPlateau();

            if($plateau->getUtilisateurs()->isEmpty()){

                $tabCase = $plateau->getCases();
                foreach($tabCase as $uneCase){ // On enlève les cases une par une

                    $tabRessource = $uneCase->getRessources();
                    foreach($tabRessource as $uneRessource){ // Pour chaque case on enlève les ressources une par une

                        $entityManager->remove($uneRessource);

                    }

                    $entityManager->remove($uneCase);
                }

                $entityManager->remove($plateau); // On supprime la partie
            }

            $entityManager->remove($partie); // On supprime la partie

            $entityManager->flush(); // On enregistre les changements en BD

            $this->addFlash('success', 'La partie a été suprimée.');
        }



        return $this->redirectToRoute('espace_partie');


    }

    /**
     * @Route("/supression/plateaux/{idPlateau}", name="supprimer_plateau")
     */
    public function supprimerUnPlateau($idPlateau, UserInterface $utilisateur, PlateauRepository $repositoryPlateau)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $plateau = $repositoryPlateau->find($idPlateau);

        if (in_array($utilisateur, $plateau->getUtilisateurs()->toArray())){ // Seul le créateur peut supprimer sa partie

            $plateau->removeUtilisateur($utilisateur);

            if($plateau->getUtilisateurs()->isEmpty() && $plateau->getParties()->isEmpty()){

                $tabCase = $plateau->getCases();
                foreach($tabCase as $uneCase){ // On enlève les cases une par une

                    $tabRessource = $uneCase->getRessources();
                    foreach($tabRessource as $uneRessource){ // Pour chaque case on enlève les ressources une par une

                        $entityManager->remove($uneRessource);

                    }

                    $entityManager->remove($uneCase);
                }

                $entityManager->remove($plateau); // On supprime la partie
            }
            else{
                $entityManager->persist($plateau);
            }

            $entityManager->flush(); // On enregistre les changements en BD

            $this->addFlash('success', 'Le plateau a été suprimée.');
        }

    return $this->redirectToRoute('espace_plateau');


    }

    /**
     * @Route("/supression/annulation-plateau/{idPlateau}", name="annuler_plateau")
     */
    public function annulerCreationPlateau($idPlateau, UserInterface $utilisateur, PlateauRepository $repositoryPlateau)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $plateau = $repositoryPlateau->find($idPlateau);

        if (in_array($utilisateur, $plateau->getUtilisateurs()->toArray())){ // Seul le créateur peut supprimer sa partie

            $plateau->removeUtilisateur($utilisateur);

            $entityManager->remove($plateau);

            $entityManager->flush(); // On enregistre les changements en BD

            $this->addFlash('success', 'La création du plateau a été annulée.');
        }

        return $this->redirectToRoute('espace_plateau');


    }

    /**
     * @Route("/supression/compte/{idCompte}", name="supprimer_compte")
     */
    public function supprimerUnCompte($idCompte, UserInterface $user, TokenStorageInterface $tokenStorage, SessionInterface $session)
    {
        $repositoryUtilisateur=$this->getDoctrine()->getRepository(Utilisateur::class);
        $userId = $user->getId();
        $utilisateur = $repositoryUtilisateur->find($userId);

        $compte = $repositoryUtilisateur->find($idCompte);

        if ($compte->getPseudo() == $utilisateur->getPseudo()){ // Seul le propriétaire du compte peut supprimer son compte

        $entityManager = $this->getDoctrine()->getManager();

        foreach ($compte->getPartiesCree() as $partie) {

        //$this->redirectToRoute('app_logout');

        $plateauEnJeu = $partie->getPlateauDeJeu();

        $tabCase = $plateauEnJeu->getCases();
        foreach($tabCase as $uneCase){ // On enlève les cases une par une

            $tabRessource = $uneCase->getRessources();
            foreach($tabRessource as $uneRessource){ // Pour chaque case on enlève les ressources une par une

                $entityManager->remove($uneRessource);

            }

            $entityManager->remove($uneCase);
        }

        $tabPion = $plateauEnJeu->getPions();
        foreach($tabPion as $unPion){ // On enlève chaque pion un par un

            $entityManager->remove($unPion);
        }

        $entityManager->remove($plateauEnJeu); // On supprime le plateauEnJeu

        $entityManager->remove($partie); // On supprime la partie

         // On enregistre les changements en BD
        }

        foreach ($compte->getPartiesRejoins() as $partieR) {
            $compte->removePartiesRejoin($partieR);
            $compte->removePlateauEnJeux($partieR->getPlateauDeJeu());
        }

        $entityManager->remove($compte);

        $entityManager->flush();



        $tokenStorage->setToken(null);
        $session->invalidate();

        return $this->redirectToRoute('accueil');
        }
        else{
            return $this->redirectToRoute('espace_compte');
        }


    }

    /**
     * @Route("/invite", name="en_invite")
     */
    public function connexionInvite(ObjectManager $manager, Request $request, GuardAuthenticatorHandler $guardHandler, LoginAuthenticator $authenticator)
    {

        // Création d'un utilisateur invité qui a des données générées aléatoirement
        $invite = new Utilisateur();

        $random = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 5, 5);

        $fakeEmail = $random."@guest.com";
        $invite->setEmail($fakeEmail);

        $fakePseudo = "Guest_".$random;
        $invite->setPseudo($fakePseudo);

        $invite->setAvatar("/img/avatarGuest.jpg");

        $roles[] =  'ROLE_INVITE';
        $invite->setRoles($roles);

        $invite->setEstInvite(true);

        $plainPassword = $random;
        $invite->setMotDePasse($this->passwordEncoder->encodePassword(
            $invite,
            $plainPassword
         ));

        // Enregistrer l'invité en base de données
        $manager->persist($invite);
        $manager->flush();

        return $guardHandler->authenticateUserAndHandleSuccess(
                $invite,
                $request,
                $authenticator,
                'main' // Nom du parefeu dans security.yaml
            );
    }

    /**
     * @Route("/compte/changement{code}", name="changement_avatar")
     */
    public function changementAvatar(ObjectManager $manager, UserInterface $utilisateur, $code)
    {

        $avatar = "/img/avatar" . $code . ".jpg";

        $utilisateur->setAvatar($avatar);

        $manager->persist($utilisateur);
        $manager->flush();

        $this->addFlash('success', 'Votre avatar a été changé.');

        return $this->redirectToRoute('espace_compte');
    }

    /**
     * @Route("/parametres/parties/{idPartie}/exclure", name="exclure_joueur")
     */
    public function exclureJoueur($idPartie, UserInterface $utilisateur, ObjectManager $manager, PartieRepository $repositoryPartie)
    {

        $partie = $repositoryPartie->find($idPartie);

        // Si l'utilsateur est le créateur de la partie, il peut
        if ($partie->getCreateur()->getPseudo() == $utilisateur->getPseudo()){

            if($partie->getPlateauDeJeu()->getJoueur() != null){

                $partie->setDateRejoins(NULL);

                $joueur = $partie->getPlateauDeJeu()->getJoueur();
                $joueur->removePartiesRejoin($partie);
                $joueur->removePlateauEnJeux($partie->getPlateauDeJeu());

                $manager->persist($joueur);
                $manager->persist($partie);
                $manager->persist($partie->getPlateauDeJeu());

                $manager->flush();

                $this->addFlash('success', 'Le joueur a été exclu.');

                return $this->redirectToRoute('partie_en_cours', ['idPartie' => $idPartie]);

            }

        }
        else
        {
            return $this->redirectToRoute('espace_partie');
        }


    }

    /**
     * @Route("/parametres/parties/{idPartie}/reinitialiser", name="reinitialiser_position")
     */
    public function reinitialiserPosition($idPartie, UserInterface $utilisateur, ObjectManager $manager, PartieRepository $repositoryPartie)
    {

        $partie = $repositoryPartie->find($idPartie);

        // Si l'utilsateur est le créateur de la partie, il peut
        if ($partie->getCreateur()->getPseudo() == $utilisateur->getPseudo()){

            $pions = $partie->getPlateauDeJeu()->getPions();
            foreach ($pions as $unPion) {
                $unPion->setAvancementPlateau(0);
                $manager->persist($unPion);
            }
            $manager->flush();

            $this->addFlash('success', 'La position des pions a été réinitialisée.');

            return $this->redirectToRoute('partie_en_cours', ['idPartie' => $idPartie]);

        }
        else
        {
            return $this->redirectToRoute('espace_partie');
        }


    }

    /**
     * @Route("/api/plateaux/{idPlateau}", name="api_plateaux")
     */
    public function apiPlateaux($idPlateau, PlateauRepository $repositoryPlateau)
    {

        $plateau = $repositoryPlateau->find($idPlateau);

        // On récupère les informations du plateau pour les retourner en json
        $nom = $plateau->getNom();
        $description = $plateau->getDescription();
        $difficulte = $plateau->getNiveauDifficulte();
        $nbCases = $plateau->getNbCases();

        $caseData = [];

        // On récupère les informations des cases du plateau pour les retourner en json
        $tabCase = $plateau->getCases();

        foreach($tabCase as $uneCase){

            $ressourceData = [];

            $numero = $uneCase->getNumero();
            $defi = $uneCase->getDescriptifDefi();
            $consignes = $uneCase->getConsignes();
            $code = $uneCase->getCodeValidation();

            $tabRessource = $uneCase->getRessources();
            foreach($tabRessource as $uneRessource){

                $chemin = $uneRessource->getChemin();
                $infosR = ['chemin' => $chemin];

                array_push($ressourceData, $infosR);

            }



            $infos = ['numero' => $numero, 'defi' => $defi, 'consignes' => $consignes, 'code' => $code, 'ressources' => $ressourceData];

            array_push($caseData, $infos);
        }

        return $this->json(['nom' => $nom, 'description' => $description, 'difficulte' => $difficulte, 'nbCases' => $nbCases, 'cases' => $caseData]);
    }

    /**
     * @Route("/api/partie/{idPartie}", name="api_parties")
     */
    public function apiPartie($idPartie, Request $request, ObjectManager $manager, PartieRepository $repositoryPartie)
    {

        $partie = $repositoryPartie->find($idPartie);

        $plateau = $partie->getPlateauDeJeu();

        $pions = $plateau->getPions();

        if ($request->getMethod() == 'POST') {

            $pionsNouveau = json_decode($request->request->get('$data'),true);

            foreach ($pions as $unPion) {

                foreach ($pionsNouveau['pions'] as $unPionNouveau) {

                    if ($unPionNouveau['player'] == $unPion->getNumeroJoueur()) {

                        $unPion->setAvancementPlateau($unPionNouveau["placement"]);
                        $manager->persist($unPion);
                        $manager->flush();

                    }

                }
            }
        }


        // On récupère les informations de la partie pour les retourner en json

        $name = $partie->getNom();

        $description = $partie->getDescription();

        $createur = $partie->getCreateur()->getPseudo();
        if ($partie->getJoueurs()->isEmpty()){
            $joueur = "";
        }
        else
        {
            $joueur = $partie->getJoueurs()->get(0)->getPseudo();
        }
        $nbPions = $partie->getNbPionParPlateau();
        $nbPlateaux = $partie->getNbPlateaux();
        $nbFacesDe = $partie->getNbFacesDe();
        $estLance = $partie->getEstLance();

        $nomPlateau = $plateau->getNom();
        $descriptionP = $plateau->getDescription();
        $difficulte = $plateau->getNiveauDifficulte();
        $nbCases = $plateau->getNbCases();

        if (is_null($plateau->getJoueur())){
            $joueurPlateau = "";
        }
        else
        {
            $joueurPlateau = $plateau->getJoueur()->getPseudo();
        }

        $arrayInfoPions = [];

        foreach ($pions as $unPion) {
            $player = $unPion->getNumeroJoueur();
            $nomPion= $unPion->getNom();
            $couleur= $unPion->getCouleur();
            $position= $unPion->getAvancementPlateau();

            array_push($arrayInfoPions, ['player' => $player, 'nom' => $nomPion, 'couleur' => $couleur, 'position' => $position]);
        }

        $caseData = [];

        // On récupère les informations des cases du plateau pour les retourner en json
        $tabCase = $plateau->getCases();

        foreach($tabCase as $uneCase){

            $ressourceData = [];

            $numero = $uneCase->getNumero();
            $defi = $uneCase->getDescriptifDefi();
            $consignes = $uneCase->getConsignes();
            $code = $uneCase->getCodeValidation();

            $tabRessource = $uneCase->getRessources();
            foreach($tabRessource as $uneRessource){

                $chemin = $uneRessource->getChemin();
                $infosR = ['chemin' => $chemin];

                array_push($ressourceData, $infosR);

            }



            $infos = ['numero' => $numero, 'defi' => $defi, 'consignes' => $consignes, 'code' => $code, 'ressources' => $ressourceData];

            array_push($caseData, $infos);
        }

        $plateauDeJeu = ['nom' => $nomPlateau, 'description' => $descriptionP, 'difficulte' => $difficulte, 'nbCases' => $nbCases, 'joueur' => $joueurPlateau,'pions' => $arrayInfoPions, 'cases' => $caseData];

        return $this->json(['nom' => $name, 'description' => $description, 'createur' => $createur, 'joueur' => $joueur, 'nbPionsParPlateau' => $nbPions, 'nbPlateaux' => $nbPlateaux, 'nbFacesDe' => $nbFacesDe, 'estLance' => $estLance, 'plateau_de_jeu' => $plateauDeJeu]);
    }

    /**
     * @Route("/testjs", name="testjs")
     */
    public function testjs()
    {
      // Création d'un plateau vierge
      $cases=new Cases();

      // Création de l'objet formulaire à partir du formulaire externalisé "PartieType"
      $formulaireCase = $this->createForm(CasesType::class, $cases);

    return $this->render('les_rois_du/testjs.html.twig', ['formCase'=>$formulaireCase->createview(),
                                                                                                ]);

    }


}
