<?php

namespace BG\CinemaBundle\Purger;

use Doctrine\ORM\EntityManagerInterface;

class FilmPurger
{
  /**
   * @var EntityManagerInterface
   */
  private $em;

  // On injecte l'EntityManager
  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function purge($days)
  {
    $filmRepository      = $this->em->getRepository('BGCinemaBundle:Film');
    $filmSkillRepository = $this->em->getRepository('BGCinemaBundle:FilmSkill');

    // date d'il y a $days jours
    $date = new \Datetime($days.' days ago');

    // On récupère les annonces à supprimer
    $listFilms = $filmRepository->getFilmsBefore($date);

    // On parcourt les annonces pour les supprimer effectivement
    foreach ($listFilms as $film) {
      // On récupère les FilmSkill liées à cette annonce
      $filmSkills = $filmSkillRepository->findBy(array('film' => $film));

      // Pour les supprimer toutes avant de pouvoir supprimer l'annonce elle-même
      foreach ($filmSkills as $filmSkill) {
        $this->em->remove($filmSkill);
      }

      // On peut maintenant supprimer l'annonce
      $this->em->remove($film);
    }

    // Et on n'oublie pas de faire un flush !
    $this->em->flush();
  }
}
