<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $lien;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPrincipale;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSecondaire;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDescription;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Article", inversedBy="image")
     */
    private $articles;

    /**
     * @return mixed
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param mixed $articles
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    public function getIsPrincipale(): ?bool
    {
        return $this->isPrincipale;
    }

    public function setIsPrincipale(bool $isPrincipale): self
    {
        $this->isPrincipale = $isPrincipale;

        return $this;
    }

    public function getIsSecondaire(): ?bool
    {
        return $this->isSecondaire;
    }

    public function setIsSecondaire(bool $isSecondaire): self
    {
        $this->isSecondaire = $isSecondaire;

        return $this;
    }

    public function getIsDescription(): ?bool
    {
        return $this->isDescription;
    }

    public function setIsDescription(bool $isDescription): self
    {
        $this->isDescription = $isDescription;

        return $this;
    }
}
