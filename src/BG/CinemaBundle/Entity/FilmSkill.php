<?php

namespace BG\CinemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="bg_film_skill")
 */
class FilmSkill
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(name="level", type="string", length=255)
   */
  private $level;

  /**
   * @ORM\ManyToOne(targetEntity="BG\CinemaBundle\Entity\Film")
   * @ORM\JoinColumn(nullable=false)
   */
  private $film;

  /**
   * @ORM\ManyToOne(targetEntity="BG\CinemaBundle\Entity\Skill")
   * @ORM\JoinColumn(nullable=false)
   */
  private $skill;

  // ... vous pouvez ajouter d'autres attributs bien sûr

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param string $level
   */
  public function setLevel($level)
  {
    $this->level = $level;
  }

  /**
   * @return string
   */
  public function getLevel()
  {
    return $this->level;
  }

  /**
   * @param Film $film
   */
  public function setFilm(Film $film)
  {
    $this->film = $film;
  }

  /**
   * @return Film
   */
  public function getFilm()
  {
    return $this->film;
  }

  /**
   * @param Skill $skill
   */
  public function setSkill(Skill $skill)
  {
    $this->skill = $skill;
  }

  /**
   * @return Skill
   */
  public function getSkill()
  {
    return $this->skill;
  }
}
