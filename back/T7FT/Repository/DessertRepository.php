<?php

namespace T7FT\Repository;

use Doctrine\ORM\EntityRepository;

class DessertRepository extends EntityRepository
{
    /**
     * @return array contenant l'ensemble des desserts de la base
     */
    public function findAllDesserts()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select('a.titre')
            ->from($this->_entityName, 'a');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array contenant le nombre de dessert commandé et la somme total pour chaque dessert de la base
     */
    public function findDessertsCommandeTotal()
    {
        $sql = "SELECT d.titre, COUNT(col.id) AS nombre_desserts, SUM(t.prix) AS prix_total
                FROM T7FT\Entity\CommandeClient col, T7FT\Entity\Dessert d, T7FT\Entity\Tarif t
                WHERE d.id = col.dessert
                AND t.id = col.tarif
                GROUP BY d.id";

        return $this->_em->createQuery($sql)->getResult();
    }
}