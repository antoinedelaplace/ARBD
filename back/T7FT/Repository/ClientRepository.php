<?php

namespace T7FT\Repository;

use Doctrine\ORM\EntityRepository;

class ClientRepository extends EntityRepository
{
    /**
     * @return array avec les noms et prenoms de tout les clients de la base
     */
    public function findAllClientName()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select('a.nom', 'a.prenom')
            ->from($this->_entityName, 'a');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $id du client
     * @return json avec les informations d'un client donné
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findClientInfo($id)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select('a.nom', 'a.prenom', 'a.civilite', 'a.age', 'a.email')
            ->from($this->_entityName, 'a')
            ->where('a.id = :id')
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getSingleResult();
    }

    /**
     * @return array contenant le nombre de commande et le montant par civilité
     */
    public function findInfoCommandeCivilite()
    {
        $sql = "SELECT c.civilite, COUNT(col.id) AS nombre_commande, SUM(t.prix) AS montant_total
                FROM T7FT\Entity\CommandeClient col, T7FT\Entity\Tarif t, T7FT\Entity\Client c
                WHERE t.id = col.tarif
                AND c.id = col.client
                GROUP BY c.civilite";

        return $this->_em->createQuery($sql)->getResult();
    }

    /**
     * @return array contenant le nombre de commande et le montant par age
     */
    public function findInfoCommandeAge()
    {
        $sql = "SELECT c.age, COUNT(col.id) AS nombre_commande, SUM(t.prix) AS montant_total
                FROM T7FT\Entity\CommandeClient col, T7FT\Entity\Tarif t, T7FT\Entity\Client c
                WHERE t.id = col.tarif
                AND c.id = col.client
                GROUP BY c.age";

        return $this->_em->createQuery($sql)->getResult();
    }

    /**
     * @return array contenant le nombre de commande et le montant par email
     */
    public function findInfoCommandeEmail()
    {
        $sql = "SELECT SUBSTRING(c.email, LOCATE('@', c.email) + 1) AS entreprise, COUNT(col.id) AS nombre_commande, SUM(t.prix) AS montant_total
                FROM T7FT\Entity\CommandeClient col, T7FT\Entity\Tarif t, T7FT\Entity\Client c
                WHERE t.id = col.tarif
                AND c.id = col.client
                GROUP BY entreprise";

        return $this->_em->createQuery($sql)->getResult();
    }
}