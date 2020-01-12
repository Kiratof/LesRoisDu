<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlateauEnJeuRepository")
 */
class PlateauEnJeu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $nom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $niveauDifficulte;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Partie", mappedBy="plateauDeJeu", cascade={"persist", "remove"})
     */
    private $partie;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pion", mappedBy="plateauEnJeu", orphanRemoval=true)
     */
    private $pions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="plateauEnJeux")
     */
    private $joueur;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cases", mappedBy="plateauEnJeu", orphanRemoval=true)
     */
    private $cases;

    public function __construct()
    {
        $this->pions = new ArrayCollection();
        $this->cases = new ArrayCollection();
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

    public function getNiveauDifficulte(): ?string
    {
        return $this->niveauDifficulte;
    }

    public function setNiveauDifficulte(string $niveauDifficulte): self
    {
        $this->niveauDifficulte = $niveauDifficulte;

        return $this;
    }

    public function getPartie(): ?Partie
    {
        return $this->partie;
    }

    public function setPartie(?Partie $partie): self
    {
        $this->partie = $partie;

        // set (or unset) the owning side of the relation if necessary
        $newPlateauDeJeu = null === $partie ? null : $this;
        if ($partie->getPlateauDeJeu() !== $newPlateauDeJeu) {
            $partie->setPlateauDeJeu($newPlateauDeJeu);
        }

        return $this;
    }

    /**
     * @return Collection|Pion[]
     */
    public function getPions(): Collection
    {
        return $this->pions;
    }

    public function addPion(Pion $pion): self
    {
        if (!$this->pions->contains($pion)) {
            $this->pions[] = $pion;
            $pion->setPlateauEnJeu($this);
        }

        return $this;
    }

    public function removePion(Pion $pion): self
    {
        if ($this->pions->contains($pion)) {
            $this->pions->removeElement($pion);
            // set the owning side to null (unless already changed)
            if ($pion->getPlateauEnJeu() === $this) {
                $pion->setPlateauEnJeu(null);
            }
        }

        return $this;
    }

    public function getJoueur(): ?Utilisateur
    {
        return $this->joueur;
    }

    public function setJoueur(?Utilisateur $joueur): self
    {
        $this->joueur = $joueur;

        return $this;
    }

    /**
     * @return Collection|Cases[]
     */
    public function getCases(): Collection
    {
        return $this->cases;
    }

    public function addCase(Cases $case): self
    {
        if (!$this->cases->contains($case)) {
            $this->cases[] = $case;
            $case->setPlateauEnJeu($this);
        }

        return $this;
    }

    public function removeCase(Cases $case): self
    {
        if ($this->cases->contains($case)) {
            $this->cases->removeElement($case);
            // set the owning side to null (unless already changed)
            if ($case->getPlateauEnJeu() === $this) {
                $case->setPlateauEnJeu(null);
            }
        }

        return $this;
    }
}
