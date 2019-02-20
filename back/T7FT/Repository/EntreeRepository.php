<?php

namespace T7FT\Repository;

use Doctrine\ORM\EntityRepository;

class EntreeRepository extends EntityRepository
{
    /**
     * @return array contenant l'ensemble des entrées de la base
     */
    public function findAllEntrees()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select('a.titre')
            ->from($this->_entityName, 'a');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array contenant le nombre d'entrée commandé et la somme total pour chaque entrée de la base
     */
    public function findEntreesCommandeTotal()
    {
        $sql = "SELECT e.titre, COUNT(col.id) AS nombre_entrees, SUM(t.prix) AS prix_total
                FROM T7FT\Entity\CommandeClient col, T7FT\Entity\Entree e, T7FT\Entity\Tarif t
                WHERE e.id = col.entree
                AND t.id = col.tarif
                GROUP BY e.id";

        return $this->_em->createQuery($sql)->getResult();
    }
}