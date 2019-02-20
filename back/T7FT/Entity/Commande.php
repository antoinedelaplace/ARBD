<?php

namespace T7FT\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 */
class Commande
{
    const TYPE_PAIEMENT_ESPECE = 'Espece';
    const TYPE_PAIEMENT_CB = 'Carte';
    const TYPE_PAIEMENT_TICKET = 'Ticket Restaurant';

    const ETAT_ATTENTE = 'En attente';
    const ETAT_COURS = 'En cours';
    const ETAT_LIVRAISON = 'Livraison';
    const ETAT_RECU = 'Recu';

    /**
     * @var \DateTime
     */
    private $dateCreation;

    /**
     * @var \DateTime
     */
    private $dateLivraison;

    /**
     * @var \DateTime
     */
    private $horaireLivraison;

    /**
     * @var string
     */
    private $typePaiement;

    /**
     * @var string
     */
    private $etat;

    /**
     * @var integer
     */
    private $paiement;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \T7FT\Entity\Client
     */
    private $acheteur;


    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Commande
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateLivraison
     *
     * @param \DateTime $dateLivraison
     * @return Commande
     */
    public function setDateLivraison($dateLivraison)
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    /**
     * Get dateLivraison
     *
     * @return \DateTime 
     */
    public function getDateLivraison()
    {
        return $this->dateLivraison;
    }

    /**
     * Set horaireLivraison
     *
     * @param \DateTime $horaireLivraison
     * @return Commande
     */
    public function setHoraireLivraison($horaireLivraison)
    {
        $this->horaireLivraison = $horaireLivraison;

        return $this;
    }

    /**
     * Get horaireLivraison
     *
     * @return \DateTime 
     */
    public function getHoraireLivraison()
    {
        return $this->horaireLivraison;
    }

    /**
     * Set typePaiement
     *
     * @param string $typePaiement
     * @return Commande
     */
    public function setTypePaiement($typePaiement)
    {
        if (!in_array($typePaiement, array(self::TYPE_PAIEMENT_CB, self::TYPE_PAIEMENT_ESPECE, self::TYPE_PAIEMENT_TICKET)))
            throw new \InvalidArgumentException("Type de paiement Invalide");
        $this->typePaiement = $typePaiement;

        return $this;
    }

    /**
     * Get typePaiement
     *
     * @return string 
     */
    public function getTypePaiement()
    {
        return $this->typePaiement;
    }

    /**
     * Set etat
     *
     * @param string $etat
     * @return Commande
     */
    public function setEtat($etat)
    {
        if (!in_array($etat, array(self::ETAT_ATTENTE, self::ETAT_COURS, self::ETAT_LIVRAISON, self::ETAT_RECU)))
            throw new \InvalidArgumentException("Etat de la commande Invalide");
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string 
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set paiement
     *
     * @param integer $paiement
     * @return Commande
     */
    public function setPaiement($paiement)
    {
        if ($paiement != 1 && $paiement != 0)
            throw new \InvalidArgumentException("Paiement Invalide");
        $this->paiement = $paiement;

        return $this;
    }

    /**
     * Get paiement
     *
     * @return integer 
     */
    public function getPaiement()
    {
        return $this->paiement;
    }

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
     * Set acheteur
     *
     * @param \T7FT\Entity\Client $acheteur
     * @return Commande
     */
    public function setAcheteur(\T7FT\Entity\Client $acheteur = null)
    {
        $this->acheteur = $acheteur;

        return $this;
    }

    /**
     * Get acheteur
     *
     * @return \T7FT\Entity\Client 
     */
    public function getAcheteur()
    {
        return $this->acheteur;
    }
}
