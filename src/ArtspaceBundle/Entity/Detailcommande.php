<?php

namespace ArtspaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Detailcommande
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ArtspaceBundle\Entity\DetailcommandeRepository")
 */
class Detailcommande
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=255)
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="integer")
     */
    private $prix;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="ArtspaceBundle\Entity\Commande")
     */
    private $commande;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     * @return Detailcommande
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set prix
     *
     * @param string $prix
     * @return Detailcommande
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set commande
     *
     * @param \ArtspaceBundle\Entity\Commande $commande
     * @return Detailcommande
     */
    public function setCommande(\ArtspaceBundle\Entity\Commande $commande = null)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \ArtspaceBundle\Entity\Commande 
     */
    public function getCommande()
    {
        return $this->commande;
    }
}
