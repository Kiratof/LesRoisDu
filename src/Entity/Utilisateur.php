<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 * @UniqueEntity("email", message="Cette adresse mail est déjà utilisée !")
 * @UniqueEntity("pseudo", message="Ce pseudo est déjà utilisé !")
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message= "L'adresse mail doit être renseignée")
     * @Assert\Email(message= "L'adresse mail n'est pas valide")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     * @Assert\NotBlank(message= "Le pseudo doit être renseigné")
     */
    private $pseudo;

    /**
     * @Assert\NotBlank(message="Le mot de passe doit être renseigné")
     * @Assert\Length(min=6,max=4096, minMessage = "Le mot de passe doit faire au minimum {{ limit }} caractères")
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motDePasse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estInvite;

    /**
     * @ORM\Column(type="text")
     */
    private $avatar;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Partie", mappedBy="joueurs")
     */
    private $partiesRejoint;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Partie", mappedBy="createur")
     */
    private $partiesCree;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PlateauEnJeu", mappedBy="joueur")
     */
    private $plateauEnJeu;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Plateau", mappedBy="utilisateurs")
     */
    private $plateaux;

    public function __construct()
    {
        $this->partiesRejoint = new ArrayCollection();
        $this->partiesCree = new ArrayCollection();
        $this->plateauEnJeu = new ArrayCollection();
        $this->plateaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;

    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        return (string) $this->motDePasse;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getEstInvite(): ?bool
    {
        return $this->estInvite;
    }

    public function setEstInvite(bool $estInvite): self
    {
        $this->estInvite = $estInvite;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Partie[]
     */
    public function getPartiesRejoint(): Collection
    {
        return $this->partiesRejoint;
    }

    public function addPartiesRejoint(Partie $partiesRejoin): self
    {
        if (!$this->partiesRejoint->contains($partiesRejoin)) {
            $this->partiesRejoint[] = $partiesRejoin;
            $partiesRejoin->addJoueur($this);
        }

        return $this;
    }

    public function removePartiesRejoin(Partie $partiesRejoin): self
    {
        if ($this->partiesRejoint->contains($partiesRejoin)) {
            $this->partiesRejoint->removeElement($partiesRejoin);
            $partiesRejoin->removeJoueur($this);
        }

        return $this;
    }

    /**
     * @return Collection|Partie[]
     */
    public function getPartiesCree(): Collection
    {
        return $this->partiesCree;
    }

    public function addPartiesCree(Partie $partiesCree): self
    {
        if (!$this->partiesCree->contains($partiesCree)) {
            $this->partiesCree[] = $partiesCree;
            $partiesCree->setCreateur($this);
        }

        return $this;
    }

    public function removePartiesCree(Partie $partiesCree): self
    {
        if ($this->partiesCree->contains($partiesCree)) {
            $this->partiesCree->removeElement($partiesCree);
            // set the owning side to null (unless already changed)
            if ($partiesCree->getCreateur() === $this) {
                $partiesCree->setCreateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PlateauEnJeu[]
     */
    public function getPlateauEnJeu(): Collection
    {
        return $this->plateauEnJeu;
    }

    public function addPlateauEnJeu(PlateauEnJeu $plateauEnJeu): self
    {
        if (!$this->plateauEnJeu->contains($plateauEnJeu)) {
            $this->plateauEnJeu[] = $plateauEnJeu;
            $plateauEnJeu->setJoueur($this);
        }

        return $this;
    }

    public function removePlateauEnJeu(PlateauEnJeu $plateauEnJeu): self
    {
        if ($this->plateauEnJeu->contains($plateauEnJeu)) {
            $this->plateauEnJeu->removeElement($plateauEnJeu);
            // set the owning side to null (unless already changed)
            if ($plateauEnJeu->getJoueur() === $this) {
                $plateauEnJeu->setJoueur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Plateau[]
     */
    public function getPlateaux(): Collection
    {
        return $this->plateaux;
    }

    public function addPlateau(Plateau $plateau): self
    {
        if (!$this->plateaux->contains($plateau)) {
            $this->plateaux[] = $plateau;
            $plateau->addUtilisateur($this);
        }

        return $this;
    }

    public function removePlateau(Plateau $plateau): self
    {
        if ($this->plateaux->contains($plateau)) {
            $this->plateaux->removeElement($plateau);
            $plateau->removeUtilisateur($this);
        }

        return $this;
    }
}
