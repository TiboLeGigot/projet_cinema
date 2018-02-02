<?php

namespace BG\CinemaBundle\Twig;

use BG\CinemaBundle\Antispam\BGAntispam;

class AntispamExtension extends \Twig_Extension
{
  /**
   * @var BGAntispam
   */
  private $bgAntispam;

  public function __construct(BGAntispam $bgAntispam)
  {
    $this->bgAntispam = $bgAntispam;
  }

  public function checkIfArgumentIsSpamm($text)
  {
    return $this->bgAntispam->isSpam($text);
  }

  // Twig execute cette méthode afin de savoir quelles fonctions de NOTRE service il doit utiliser
  public function getFunctions()
  {
    return [new \Twig_SimpleFunction('checkIfSpamm', [$this, 'checkIfArgumentIsSpamm']) ];
  }

  // Obligatoire pour identifier l'extension Twig
  public function getName()
  {
    return 'BGAntispam';
  }
}
