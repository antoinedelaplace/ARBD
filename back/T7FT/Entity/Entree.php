<?php

namespace T7FT\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entree
 */
class Entree
{
    /**
     * @var string
     */
    private $titre;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set titre
     *
     * @param string $titre
     * @return Entree
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
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
}
