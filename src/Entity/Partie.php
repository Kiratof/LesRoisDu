<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartieRepository")
 */
class Partie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank(message= "Le nom doit être renseigné")
     * @Assert\Length(
     *      min = 4,
     *      max = 40,
     *      minMessage = "Le nom doit au minimum faire {{ limit }} caractères de long",
     *      maxMessage = "Le nom doit au maximum faire {{ limit }} caractères de long",
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *      min = 4,
     *      max = 350,
     *      minMessage = "La description doit au minimum faire {{ limit }} caractères de long",
     *      maxMessage = "La description doit au maximum faire {{ limit }} caractères de long",
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $code;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Utilisateur", inversedBy="partiesRejoint")
     */
    private $joueurs;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="partiesCree")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createur;

    /**
     * @ORM\Column(type="smallint")
     */
    private $nbPlateaux;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estLance;

    /**
     * @ORM\Column(type="datetime")
     */
    private $derniereModification;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRejoins;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PlateauEnJeu", cascade={"persist"}, mappedBy="partie", orphanRemoval=true)
     */
    private $plateauEnJeu;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Plateau", inversedBy="parties")
     */
    private $plateau;

    public function __construct()
    {
        $this->joueurs = new ArrayCollection();
        $this->plateauEnJeu = new ArrayCollection();
        $this->plateau = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getJoueurs(): Collection
    {
        return $this->joueurs;
    }

    public function addJoueur(Utilisateur $joueur): self
    {
        if (!$this->joueurs->contains($joueur)) {
            $this->joueurs[] = $joueur;
        }

        return $this;
    }

    public function removeJoueur(Utilisateur $joueur): self
    {
        if ($this->joueurs->contains($joueur)) {
            $this->joueurs->removeElement($joueur);
        }

        return $this;
    }

    public function getCreateur(): ?Utilisateur
    {
        return $this->createur;
    }

    public function setCreateur(?Utilisateur $createur): self
    {
        $this->createur = $createur;

        return $this;
    }

    public function getNbPlateaux(): ?int
    {
        return $this->nbPlateaux;
    }

    public function setNbPlateaux(int $nbPlateaux): self
    {
        $this->nbPlateaux = $nbPlateaux;

        return $this;
    }


    public function getEstLance(): ?bool
    {
        return $this->estLance;
    }

    public function setEstLance(bool $estLance): self
    {
        $this->estLance = $estLance;

        return $this;
    }

    public function getDerniereModification(): ?\DateTimeInterface
    {
        return $this->derniereModification;
    }

    public function setDerniereModification(\DateTimeInterface $derniereModification): self
    {
        $this->derniereModification = $derniereModification;

        return $this;
    }

    public function getDateRejoins(): ?\DateTimeInterface
    {
        return $this->dateRejoins;
    }

    public function setDateRejoins(?\DateTimeInterface $dateRejoins): self
    {
        $this->dateRejoins = $dateRejoins;

        return $this;
    }

    /**
     * @return Collection|plateauEnJeu[]
     */
    public function getPlateauEnJeu(): Collection
    {
        return $this->plateauEnJeu;
    }

    public function addPlateauEnJeu(plateauEnJeu $plateauEnJeu): self
    {
        if (!$this->plateauEnJeu->contains($plateauEnJeu)) {
            $this->plateauEnJeu[] = $plateauEnJeu;
            $plateauEnJeu->setPartie($this);
        }

        return $this;
    }

    public function removePlateauEnJeu(plateauEnJeu $plateauEnJeu): self
    {
        if ($this->plateauEnJeu->contains($plateauEnJeu)) {
            $this->plateauEnJeu->removeElement($plateauEnJeu);
            // set the owning side to null (unless already changed)
            if ($plateauEnJeu->getPartie() === $this) {
                $plateauEnJeu->setPartie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Plateau[]
     */
    public function getPlateau(): Collection
    {
        return $this->plateau;
    }

    public function addPlateau(Plateau $plateau): self
    {
        if (!$this->plateau->contains($plateau)) {
            $this->plateau[] = $plateau;
        }

        return $this;
    }

    public function removePlateau(Plateau $plateau): self
    {
        if ($this->plateau->contains($plateau)) {
            $this->plateau->removeElement($plateau);
        }

        return $this;
    }
}
