<?php
namespace App\Controller;

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
use App\Form\UtilisateurType;
use App\Form\PartieType;
use App\Form\PlateauType;
use App\Form\CasesType;
use App\Security\LoginAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;


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
  public function CGU(UserInterface $user)
  {
    return $this->render('les_rois_du/CGU.html.twig', ['utilisateur'=> $user]);
  }

  /**
  * @Route("/but-application", name="ButApplication")
  */
  public function ButApplication(UserInterface $user)
  {
    return $this->render('les_rois_du/ButApplication.html.twig', ['utilisateur'=> $user]);
  }

  /**
  * @Route("/credits", name="credits")
  */
  public function affichageCredits(UserInterface $user)
  {
    return $this->render('les_rois_du/credits.html.twig', ['utilisateur'=> $user]);
  }

  /**
  * @Route("/aide", name="aides")
  */
  public function affichageAides(UserInterface $user)
  {
    return $this->render('les_rois_du/aides.html.twig', ['utilisateur'=> $user]);
  }

  /**
  * @Route("/mentions-legales", name="mentions_legales")
  */
  public function affichageMentionsLegales(UserInterface $user)
  {
    return $this->render('les_rois_du/mentionslegales.html.twig', ['utilisateur'=> $user]);
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

      // $repositoryPlateau=$this->getDoctrine()->getRepository(Plateau::class);
      //
      // $plateauOriginel = $repositoryPlateau->find(1);
      //
      // $plateau = new Plateau();
      //
      // $date = New \DateTime();
      // $plateau->setDerniereModification($date);
      //
      // $plateau->setNom($plateauOriginel->getNom());
      // $plateau->setDescription($plateauOriginel->getDescription());
      // $plateau->setNiveauDifficulte($plateauOriginel->getNiveauDifficulte());
      // $plateau->setNbCases($plateauOriginel->getNbCases());
      // $code = strtoupper(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 5, 5));
      // $plateau->setCode($code);
      //
      // $tabCase = $plateauOriginel->getCases();
      // foreach($tabCase as $uneCase){
      //   $cases = new Cases();
      //   $cases->setDescriptifDefi($uneCase->getDescriptifDefi())
      //   ->setConsignes($uneCase->getConsignes())
      //   ->setCodeValidation($uneCase->getCodeValidation())
      //   ->setNumero($uneCase->getNumero())
      //   ;
      //
      //   $cases->setPlateau($plateau);
      //
      //   $manager->persist($cases);
      //
      //   // On récupère les ressources des cases du plateau et les copie une par une dans les cases du plateau
      //   $tabRessource = $uneCase->getRessources();
      //   foreach($tabRessource as $uneRessource){
      //     $ressource = new Ressource();
      //
      //     $ressource->setChemin($uneRessource->getChemin());
      //
      //
      //     $ressource->setCases($cases);
      //     $cases->addRessource($ressource);
      //     $manager->persist($cases);
      //
      //     $manager->persist($ressource);
      //
      //   }
      // }
      //
      // $utilisateur->addPlateau($plateau);
      //
      // $manager->persist($plateau);


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
    $partie = new Partie();

    // Création de l'objet formulaire à partir du formulaire externalisé "PartieType"
    $formulairePartie = $this->createForm(PartieType::class, $partie);

    $formulairePartie->handleRequest($request);
    if ($formulairePartie->isSubmitted() && $formulairePartie->isValid())
    {
      $donneesPions = [
        ['numero' => 1, 'nom' => "vert", 'couleur' => "green"],
        ['numero' => 2, 'nom' => "rouge", 'couleur' => "red"],
        ['numero' => 3, 'nom' => "jaune", 'couleur' => "yellow"],
        ['numero' => 4, 'nom' => "bleu", 'couleur' => "blue"]
      ];

      $partie->setDerniereModification(New \DateTime());

      $plateaux = $partie->getPlateau();
      foreach ($plateaux as $plateau) {
        // On copie leS plateauX sélectionné dans leS plateauEnJeu de la partie
        $plateauEnJeu = new PlateauEnJeu();
        $plateauEnJeu->setNom($plateau->getNom());
        $plateauEnJeu->setDescription($plateau->getDescription());
        $plateauEnJeu->setNiveauDifficulte($plateau->getNiveauDifficulte());
        $plateauEnJeu->setNbCases($plateau->getNbCases());
        $plateauEnJeu->setNbPion($plateau->getNbPion());
        $plateauEnJeu->setNbFaceDe($plateau->getNbFaceDe());
        $plateauEnJeu->setJoueur($user);
        $plateauEnJeu->setPartie($partie);

        $partie->addPlateauEnJeu($plateauEnJeu);

        //GESTION DES PIONS
        for ($i=0; $i < $plateauEnJeu->getNbPion(); $i++) {

          $pion = new Pion();
          $pion->setNumeroJoueur($donneesPions[$i]["numero"]);
          $pion->setNom($donneesPions[$i]["nom"]);
          $pion->setCouleur($donneesPions[$i]["couleur"]);
          $pion->setAvancementPlateau(0);
          $pion->setPlateauEnJeu($plateauEnJeu);

          $manager->persist($pion);
        }

        //GESTION DES CASES
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
      }


      $partie->setCreateur($user);
      $code = strtoupper(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 5, 5));
      $partie->setCode($code);
      $partie->setEstLance(false);
      $partie->setNbPlateaux(count($plateaux));

      $user->addPartiesCree($partie);

      $manager->persist($partie);
      $manager->persist($user);

      // Enregistrer en base de données
      $manager->flush();

      $this->addFlash('success', 'La partie a été créée.');

      // Rediriger l'utilisateur vers la page d'accueil
      return $this->redirectToRoute('espace_partie');
    }
    return $this->render('les_rois_du/creationpartie.html.twig', ['vueFormulaireCreationPartie'=>$formulairePartie->createview(), 'action' => 'creer', 'partie' => null, 'utilisateur'=> $user
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
    $formulairePartie = $this->createForm(PartieType::class, $partie);
    $formulairePartie->handleRequest($request);

        if ($formulairePartie->isSubmitted() && $formulairePartie->isValid())
        {
          $partie->setDerniereModification(New \DateTime());

          // Enregistrer en base de données
          $manager->persist($partie);
          $manager->flush();

          $this->addFlash('success', 'La partie a été modifiée.');

          // Rediriger l'utilisateur vers la page d'accueil
          return $this->redirectToRoute('partie_en_cours', ['idPartie' => $idPartie]);
        }

        return $this->render('les_rois_du/creationpartie.html.twig', [
          'vueFormulaireCreationPartie'=>$formulairePartie->createview(),
          'action' => 'modifier',
          'partie' => $partie,
          'utilisateur'=> $user
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

    // Création d'une partie vierge
    $plateau = new Plateau();

    // Création de l'objet formulaire à partir du formulaire externalisé "PartieType"
    $formulairePlateau = $this->createForm(PlateauType::class, $plateau);

    $formulairePlateau->handleRequest($request);

    if ($formulairePlateau->isSubmitted() && $formulairePlateau->isValid())
    {
      //Gestion des cases créés, renseignement des informations non renseignées
      $cases = $plateau->getCases();
      $i = 1;
      foreach ($cases as $case) {
        $case->setNumero($i);
        $case->setPlateau($plateau);
        $i += 1;
      }

      //Gestion du plateau
      $date = New \DateTime();
      $plateau->setDerniereModification($date);
      $code = strtoupper(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 5, 5));
      $plateau->setCode($code);
      $plateau->setNbCases(count($cases));
      $plateau->addUtilisateur($user);

      // Enregistrer en base de données
      $manager->persist($plateau);
      $manager->flush();

      $this->addFlash('success', 'Le plateau a été créé, vous pouvez désormais l\'utiliser dans une partie');

      // Rediriger l'utilisateur vers la page espace_plateau
      return $this->redirectToRoute('espace_plateau');
    }
    return $this->render('les_rois_du/creationplateau.html.twig', ['vueFormulaireCreationPlateau'=>$formulairePlateau->createview(), 'action' => 'creer', 'plateau' => null, 'utilisateur'=>$user
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

      $formulairePlateau = $this->createForm(PlateauType::class, $plateau);

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
      return $this->render('les_rois_du/creationplateau.html.twig',[
       'vueFormulaireCreationPlateau'=>$formulairePlateau->createview(),
       'action' => 'modifier',
       'plateau' => $plateau,
       'utilisateur'=> $user
    ]);
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
    $plateauxEnJeu = $partie->getPlateauEnJeu();
    if($partie->getJoueurs()->isEmpty()){

      $date = new \DateTime();
      $partie->setDateRejoins($date);

      $user->addPartiesRejoint($partie);
      foreach ($plateauxEnJeu as $plateauEnJeu) {
        $user->addPlateauEnJeu($plateauEnJeu);
        $manager->persist($plateauEnJeu);
      }

      // Enregistrer les ressources modifiées en base de données
      $manager->persist($partie);
      $manager->persist($user);
      $manager->flush();
      $this->addFlash('success', 'Vous avez rejoint la partie.');

    }
    else
    {
      $this->addFlash('echec', 'Vous ne pouvez pas rejoindre cette partie car le nombre maximum de joueurs a été atteint !');
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

    $plateauxEnJeu = $partie->getPlateauEnJeu();
    foreach ($plateauxEnJeu as $plateauEnJeu) {

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

    }

    //Supprime les plateaux qui n'ont plus d'utilisateur
    // $plateau = $partie->getPlateau();
    //
    // if($plateau->getUtilisateurs()->isEmpty()){
    //
    //     $tabCase = $plateau->getCases();
    //     foreach($tabCase as $uneCase){ // On enlève les cases une par une
    //
    //         $tabRessource = $uneCase->getRessources();
    //         foreach($tabRessource as $uneRessource){ // Pour chaque case on enlève les ressources une par une
    //
    //             $entityManager->remove($uneRessource);
    //
    //         }
    //
    //         $entityManager->remove($uneCase);
    //     }
    //
    //     $entityManager->remove($plateau); // On supprime la partie
    // }

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

    $this->addFlash('success', 'Le plateau a été suprimé.');
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

      $plateauEnJeu = $partie->getplateauDeJeu();

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

    foreach ($compte->getPartiesRejoint() as $partieR) {
      $compte->removePartiesRejoint($partieR);
      $compte->removePlateauEnJeux($partieR->getplateauDeJeu());
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

    if($partie->getplateauDeJeu()->getJoueur() != null){

      $partie->setDateRejoins(NULL);

      $joueur = $partie->getplateauDeJeu()->getJoueur();
      $joueur->removePartiesRejoint($partie);
      $joueur->removePlateauEnJeux($partie->getplateauDeJeu());

      $manager->persist($joueur);
      $manager->persist($partie);
      $manager->persist($partie->getplateauDeJeu());

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

    $pions = $partie->getplateauDeJeu()->getPions();
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
  //LECTURE dans l'api
  //On récupère la partie
  $partie = $repositoryPartie->find($idPartie);

  //Informations relatives à la partie
  $name = $partie->getNom();
  $description = $partie->getDescription();
  $nbPlateaux = $partie->getNbPlateaux();
  $createur = $partie->getCreateur()->getPseudo();
  $estLance = $partie->getEstLance();
  $derniereModification = $partie->getDerniereModification();

  if ($partie->getJoueurs()->isEmpty()){
    $joueur = "";
  }
  else
  {
    $joueur = $partie->getJoueurs()->get(0)->getPseudo();
  }

  //Informations relatives aux plateaux
  $plateaux = $partie->getPlateauEnJeu();

  $plateauInfo = [];
  $tabPlateaux = [];

  foreach ($plateaux as $plateau) {

    //Informations relatives au plateau
    $nom = $plateau->getNom();
    $plateauInfo[$nom]['nom'] = $plateau->getNom();
    $plateauInfo[$nom]['description'] = $plateau->getDescription();
    $plateauInfo[$nom]['difficulte'] = $plateau->getNiveauDifficulte();
    $plateauInfo[$nom]['nombre_de_pion'] = $plateau->getNbPion();
    $plateauInfo[$nom]['nombre_de_face_de'] = $plateau->getNbFaceDe();
    $plateauInfo[$nom]['nombre_de_cases'] = $plateau->getNbCases();

    //GESTION DES PIONS
    $tabPions = [];
    $pions = $plateau->getPions();
    foreach ($pions as $unPion) {
      $player = $unPion->getNumeroJoueur();
      $nomPion= $unPion->getNom();
      $couleur= $unPion->getCouleur();
      $position= $unPion->getAvancementPlateau();

      $pionNormalized = ['player' => $player, 'nom' => $nomPion, 'couleur' => $couleur, 'position' => $position];
      array_push($tabPions, $pionNormalized);
    }
    $plateauInfo[$nom]['pions'] = $tabPions;

    //GESTION DES CASES
    // On récupère les informations des cases du plateau pour les retourner en json
    $tabCase = $plateau->getCases();
    $caseData = [];
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
      $plateauInfo[$nom]['cases'] = $caseData;

    }
    array_push($tabPlateaux, $plateauInfo[$nom]);
  }

  //ECRITURE dans l'api
  if ($request->getMethod() == 'POST') {

    //Récupération de la requête post
    $pionDataToSet = json_decode($request->request->get('$data'),true);

    //Extraction des données de la requête
    $plateauPion = $pionDataToSet['plateau'];
    $pionPlayer = $pionDataToSet['player'];
    $pionPosition = $pionDataToSet['position'];

    //Selection du pion concerné
    $plateau = $plateaux[$plateauPion];
    $pions = $plateau->getPions();
    $pion = $pions[$pionPlayer];
    $pion->setAvancementPlateau($pionPosition);

    $manager->persist($pion);
    $manager->flush();
  }
  return $this->json(['nom' => $name, 'description' => $description, 'createur' => $createur, 'joueur' => $joueur, 'nbPlateaux' => $nbPlateaux, 'estLance' => $estLance, 'plateaux' => $tabPlateaux]);

}
}
