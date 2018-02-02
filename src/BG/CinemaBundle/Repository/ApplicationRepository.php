<?php

namespace BG\CinemaBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ApplicationRepository extends EntityRepository
{
  public function getApplicationsWithFilm($limit)
  {
    $qb = $this->createQueryBuilder('a');

    // On fait une jointure avec l'entité Film avec pour alias « adv »
    $qb
      ->innerJoin('a.film', 'adv')
      ->addSelect('adv')
    ;

    // Puis on ne retourne que $limit résultats
    $qb->setMaxResults($limit);

    // Enfin, on retourne le résultat
    return $qb
      ->getQuery()
      ->getResult()
    ;
  }
}
