<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CasesRepository")
 */
class Cases
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $descriptifDefi;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $consignes;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $codeValidation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PlateauEnJeu", inversedBy="cases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plateauEnJeu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plateau", inversedBy="cases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plateau;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ressource", mappedBy="cases", orphanRemoval=true)
     */
    private $ressources;

    public function __construct()
    {
        $this->ressources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptifDefi(): ?string
    {
        return $this->descriptifDefi;
    }

    public function setDescriptifDefi(string $descriptifDefi): self
    {
        $this->descriptifDefi = $descriptifDefi;

        return $this;
    }

    public function getConsignes(): ?string
    {
        return $this->consignes;
    }

    public function setConsignes(?string $consignes): self
    {
        $this->consignes = $consignes;

        return $this;
    }

    public function getCodeValidation(): ?string
    {
        return $this->codeValidation;
    }

    public function setCodeValidation(?string $codeValidation): self
    {
        $this->codeValidation = $codeValidation;

        return $this;
    }

    public function getPlateauEnJeu(): ?PlateauEnJeu
    {
        return $this->plateauEnJeu;
    }

    public function setPlateauEnJeu(?PlateauEnJeu $plateauEnJeu): self
    {
        $this->plateauEnJeu = $plateauEnJeu;

        return $this;
    }

    public function getPlateau(): ?Plateau
    {
        return $this->plateau;
    }

    public function setPlateau(?Plateau $plateau): self
    {
        $this->plateau = $plateau;

        return $this;
    }

    /**
     * @return Collection|Ressource[]
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources[] = $ressource;
            $ressource->setCases($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->contains($ressource)) {
            $this->ressources->removeElement($ressource);
            // set the owning side to null (unless already changed)
            if ($ressource->getCases() === $this) {
                $ressource->setCases(null);
            }
        }

        return $this;
    }
}
