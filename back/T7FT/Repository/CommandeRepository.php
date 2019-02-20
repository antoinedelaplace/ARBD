<?php

namespace T7FT\Repository;

use Doctrine\ORM\EntityRepository;

class CommandeRepository extends EntityRepository
{
    /**
     * @return array contenant les infos principales de toutes les commandes du jour
     */
    public function findCommandesDuJour()
    {
        $now = new \DateTime();
        $sql = "SELECT c.nom, c.prenom, co.id, co.dateLivraison, co.horaireLivraison, COUNT(r.id) AS nombre_repas, SUM(t.prix) AS prix_total
                FROM T7FT\Entity\Commande co, T7FT\Entity\CommandeClient col, T7FT\Entity\Repas r, T7FT\Entity\Client c, T7FT\Entity\Tarif t
                WHERE co.acheteur = c.id
                AND col.commande = co.id
                AND col.tarif = t.id
                AND r.id = col.repas
                AND co.dateLivraison = :dateLivraison
                GROUP BY co.id
                ORDER BY co.horaireLivraison ASC";
        $query = $this->_em->createQuery($sql)
            ->setParameter('dateLivraison', $now->format('Y-m-d'));

        $commandes = $query->getResult();

        for($i=0;$i<count($commandes);$i++) {
            $commandes[$i]['dateLivraison'] = $commandes[$i]['dateLivraison']->format('Y-m-d');
            $commandes[$i]['horaireLivraison'] = $commandes[$i]['horaireLivraison']->format('H:i');
        }

        return $commandes;
    }

    /**
     * @param $id de la commande concernée
     * @return array contenant l'ensemble des infos d'une commande
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findInfoCommande($id)
    {
        $sql = "SELECT r.titre as repas, e.titre as entree, d.titre as dessert,
                        c.nom, c.prenom, c.age, c.civilite,c.email, t.titre as tarif
                FROM T7FT\Entity\CommandeClient col, T7FT\Entity\Repas r, T7FT\Entity\Client c,
                T7FT\Entity\Entree e, T7FT\Entity\Dessert d, T7FT\Entity\Tarif t
                WHERE col.commande = :id
                AND c.id = col.client
                AND e.id = col.entree
                AND d.id = col.dessert
                AND t.id = col.tarif
                AND r.id = col.repas";
        $query = $this->_em->createQuery($sql)
            ->setParameter('id', $id);

        $details_commande = $query->getResult();

        $sql = "SELECT co.dateLivraison, co.horaireLivraison, co.typePaiement, co.etat, co.paiement
                FROM T7FT\Entity\Commande co
                WHERE co.id = :id";
        $query = $this->_em->createQuery($sql)
            ->setParameter('id', $id);

        $commande = $query->getSingleResult();

        $commande['dateLivraison'] = $commande['dateLivraison']->format('Y-m-d');
        $commande['horaireLivraison'] = $commande['horaireLivraison']->format('H:i');

        return array("commande" => $commande, "ligneCommande" => $details_commande);
    }

    /**
     * @return array contenant le nombre de repas total et le montant total des commandes
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findStatsCommande()
    {
        $sql = "SELECT COUNT(r.id) as repas_total, SUM(t.prix) as montant_total
                FROM T7FT\Entity\CommandeClient col, T7FT\Entity\Repas r, T7FT\Entity\Tarif t
                WHERE r.id = col.repas
                AND t.id = col.tarif";
        $query = $this->_em->createQuery($sql);

        $stats = $query->getSingleResult();

        $sql = "SELECT COUNT(co.id) as commande_total
                FROM T7FT\Entity\Commande co";
        $query = $this->_em->createQuery($sql);

        $stats_commande = $query->getSingleResult();

        return array("commande_total" => $stats_commande['commande_total'], "repas_total" => $stats['repas_total'], "prix_total" => $stats['montant_total']);
    }

    /**
     * @return array contenant le nombre de commande par type de paiement
     */
    public function findStatsCommandePaiement()
    {
        $sql = "SELECT co.typePaiement, COUNT(co.id) as nombre_commande
                FROM T7FT\Entity\Commande co
                GROUP BY co.typePaiement";
        $query = $this->_em->createQuery($sql);

        $stats = $query->getResult();

        return $stats;
    }

    /**
     * @return array contenant le nombre de commande et le montant par jour
     */
    public function findStatsCommandeJour()
    {
        $sql = "SELECT co.dateLivraison, COUNT(r.id) as nombre_repas, SUM(t.prix) as montant
                FROM T7FT\Entity\Commande co,T7FT\Entity\CommandeClient col, T7FT\Entity\Repas r, T7FT\Entity\Tarif t
                WHERE col.commande = co.id
                AND r.id = col.repas
                AND t.id = col.tarif
                GROUP BY co.dateLivraison";
        $query = $this->_em->createQuery($sql);

        $stats = $query->getResult();

        for($i=0;$i<count($stats);$i++) {
            $stats[$i]['dateLivraison'] = $stats[$i]['dateLivraison']->format('Y-m-d');
        }

        return $stats;
    }

    /**
     * @return array contenant le nombre de commande et le montant par horaire
     */
    public function findStatsCommandeHoraire()
    {
        $sql = "SELECT co.horaireLivraison, COUNT(r.id) as nombre_repas, SUM(t.prix) as montant
                FROM T7FT\Entity\Commande co,T7FT\Entity\CommandeClient col, T7FT\Entity\Repas r,  T7FT\Entity\Tarif t
                WHERE col.commande = co.id
                AND r.id = col.repas
                AND t.id = col.tarif
                GROUP BY co.horaireLivraison";
        $query = $this->_em->createQuery($sql);

        $stats = $query->getResult();

        for($i=0;$i<count($stats);$i++) {
            $stats[$i]['horaireLivraison'] = $stats[$i]['horaireLivraison']->format('H:i');
        }

        return $stats;
    }

    /**
     * @return array contenant le nombre de commande et le montant par type de tarif
     */
    public function findStatsCommandeTarif()
    {
        $sql = "SELECT t.titre, COUNT(r.id) as nombre_repas, SUM(t.prix) as montant
                FROM T7FT\Entity\Commande co,T7FT\Entity\CommandeClient col, T7FT\Entity\Repas r, T7FT\Entity\Tarif t
                WHERE col.commande = co.id
                AND r.id = col.repas
                AND t.id = col.tarif
                GROUP BY t.titre";
        $query = $this->_em->createQuery($sql);

        $stats = $query->getResult();

        return $stats;
    }
}