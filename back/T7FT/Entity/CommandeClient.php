<?php

namespace T7FT\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeClient
 */
class CommandeClient
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \T7FT\Entity\Entree
     */
    private $entree;

    /**
     * @var \T7FT\Entity\Dessert
     */
    private $dessert;

    /**
     * @var \T7FT\Entity\Tarif
     */
    private $tarif;

    /**
     * @var \T7FT\Entity\Repas
     */
    private $repas;

    /**
     * @var \T7FT\Entity\Client
     */
    private $client;

    /**
     * @var \T7FT\Entity\Commande
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
     * Set entree
     *
     * @param \T7FT\Entity\Entree $entree
     * @return CommandeClient
     */
    public function setEntree(\T7FT\Entity\Entree $entree = null)
    {
        $this->entree = $entree;

        return $this;
    }

    /**
     * Get entree
     *
     * @return \T7FT\Entity\Entree 
     */
    public function getEntree()
    {
        return $this->entree;
    }

    /**
     * Set dessert
     *
     * @param \T7FT\Entity\Dessert $dessert
     * @return CommandeClient
     */
    public function setDessert(\T7FT\Entity\Dessert $dessert = null)
    {
        $this->dessert = $dessert;

        return $this;
    }

    /**
     * Get dessert
     *
     * @return \T7FT\Entity\Dessert 
     */
    public function getDessert()
    {
        return $this->dessert;
    }

    /**
     * Set tarif
     *
     * @param \T7FT\Entity\Tarif $tarif
     * @return CommandeClient
     */
    public function setTarif(\T7FT\Entity\Tarif $tarif = null)
    {
        $this->tarif = $tarif;

        return $this;
    }

    /**
     * Get tarif
     *
     * @return \T7FT\Entity\Tarif 
     */
    public function getTarif()
    {
        return $this->tarif;
    }

    /**
     * Set repas
     *
     * @param \T7FT\Entity\Repas $repas
     * @return CommandeClient
     */
    public function setRepas(\T7FT\Entity\Repas $repas = null)
    {
        $this->repas = $repas;

        return $this;
    }

    /**
     * Get repas
     *
     * @return \T7FT\Entity\Repas 
     */
    public function getRepas()
    {
        return $this->repas;
    }

    /**
     * Set client
     *
     * @param \T7FT\Entity\Client $client
     * @return CommandeClient
     */
    public function setClient(\T7FT\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \T7FT\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set commande
     *
     * @param \T7FT\Entity\Commande $commande
     * @return CommandeClient
     */
    public function setCommande(\T7FT\Entity\Commande $commande = null)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \T7FT\Entity\Commande 
     */
    public function getCommande()
    {
        return $this->commande;
    }
}
