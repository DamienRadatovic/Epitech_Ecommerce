<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotNull
     * @var string
     */
    protected $deliveryCountry;

    /**
     * @var string
     * @Assert\NotNull
     * @ORM\Column(type="string")
     */
    protected $deliveryCity;

    /**
     * @var string
     * @Assert\NotNull
     * @ORM\Column(type="string")
     */
    protected $deliveryZip;

    /**
     * @var string
     * @Assert\NotNull
     * @ORM\Column(type="string")
     */
    protected $mail_vendeur;

    /**
     * @var string
     * @Assert\NotNull
     * @ORM\Column(type="string")
     */
    protected $nom_vendeur;

    /**
     * @var int
     * @Assert\NotNull
     * @ORM\Column(type="integer")
     */
    protected $code_command;

    /**
     * @return int
     */
    public function getCodeCommand()
    {
        return $this->code_command;
    }

    /**
     * @param int $code_command
     */
    public function setCodeCommand(int $code_command)
    {
        $this->code_command = $code_command;
    }

    /**
     * @return string
     */
    public function getMailVendeur()
    {
        return $this->mail_vendeur;
    }

    /**
     * @param string $mail_vendeur
     * @return Commande
     */
    public function setMailVendeur(string $mail_vendeur)
    {
        $this->mail_vendeur = $mail_vendeur;
        return $this;
    }

    /**
     * @return string
     */
    public function getNomVendeur()
    {
        return $this->nom_vendeur;
    }

    /**
     * @param string $nom_vendeur
     * @return Commande
     */
    public function setNomVendeur(string $nom_vendeur)
    {
        $this->nom_vendeur = $nom_vendeur;
        return $this;
    }

    /**
     * @var string
     * @Assert\NotNull
     * @ORM\Column(type="string")
     */
    protected $deliveryAddress;

    /**
     * @return string
     */
    public function getDeliveryCountry()
    {
        return $this->deliveryCountry;
    }

    /**
     * @param string $deliveryCountry
     */
    public function setDeliveryCountry(string $deliveryCountry)
    {
        $this->deliveryCountry = $deliveryCountry;
    }

    /**
     * @return string
     */
    public function getDeliveryCity()
    {
        return $this->deliveryCity;
    }

    /**
     * @param string $deliveryCity
     */
    public function setDeliveryCity(string $deliveryCity)
    {
        $this->deliveryCity = $deliveryCity;
    }

    /**
     * @return string
     */
    public function getDeliveryZip()
    {
        return $this->deliveryZip;
    }

    /**
     * @param string $deliveryZip
     */
    public function setDeliveryZip(string $deliveryZip)
    {
        $this->deliveryZip = $deliveryZip;
    }

    /**
     * @return string
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    /**
     * @param string $deliveryAddress
     */
    public function setDeliveryAddress(string $deliveryAddress)
    {
        $this->deliveryAddress = $deliveryAddress;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCommande;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modeLivraison;

    /**
     * @ORM\Column(type="integer")
     */
    private $qte;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $prixArticleCommande;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $fraisPort;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commandes")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="commande")
     */
    private $article;

    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param mixed $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }



    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getModeLivraison(): ?string
    {
        return $this->modeLivraison;
    }

    public function setModeLivraison(string $modeLivraison): self
    {
        $this->modeLivraison = $modeLivraison;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getPrixArticleCommande(): ?string
    {
        return $this->prixArticleCommande;
    }

    public function setPrixArticleCommande(string $prixArticleCommande): self
    {
        $this->prixArticleCommande = $prixArticleCommande;

        return $this;
    }

    public function getFraisPort(): ?string
    {
        return $this->fraisPort;
    }

    public function setFraisPort(string $fraisPort): self
    {
        $this->fraisPort = $fraisPort;

        return $this;
    }
}
