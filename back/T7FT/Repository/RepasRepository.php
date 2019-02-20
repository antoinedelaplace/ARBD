<?php

namespace T7FT\Repository;

use Doctrine\ORM\EntityRepository;

class RepasRepository extends EntityRepository
{
    /**
     * @return array contenant l'ensemble des repas de la base
     */
    public function findAllRepas()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select('a.titre')
            ->from($this->_entityName, 'a');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array contenant le nombre de repas commandé et la somme total pour chaque plat de la base
     */
    public function findRepasCommandeTotal()
    {
        $sql = "SELECT r.titre, COUNT(col.id) AS nombre_repas, SUM(t.prix) AS prix_total
                FROM T7FT\Entity\CommandeClient col, T7FT\Entity\Repas r, T7FT\Entity\Tarif t
                WHERE r.id = col.repas
                AND t.id = col.tarif
                GROUP BY r.id";

        return $this->_em->createQuery($sql)->getResult();
    }

    /**
     * @return array contenant le nombre d'entrees et de dessert commandés par repas
     */
    public function findEntreeDessertParRepas()
    {
        $sql = "SELECT r.titre AS repas, e.titre AS entree, COUNT(e.id) AS nombre_vente
                FROM T7FT\Entity\CommandeClient col, T7FT\Entity\Repas r, T7FT\Entity\Entree e, T7FT\Entity\Tarif t
                WHERE r.id = col.repas
                AND e.id = col.entree
                AND t.id = col.tarif
                GROUP BY r.id, e.titre";

        $query = $this->_em->createQuery($sql);

        $entrees = $query->getResult();

        $sql = "SELECT r.titre AS repas, d.titre AS dessert, COUNT(d.id) AS nombre_vente
                FROM T7FT\Entity\CommandeClient col, T7FT\Entity\Repas r, T7FT\Entity\Dessert d, T7FT\Entity\Tarif t
                WHERE r.id = col.repas
                AND d.id = col.dessert
                AND t.id = col.tarif
                GROUP BY r.id, d.titre";

        $query = $this->_em->createQuery($sql);

        $desserts = $query->getResult();

        return array("entrees" => $entrees, "desserts" => $desserts);

        return $this->_em->createQuery($sql)->getResult();
    }
}