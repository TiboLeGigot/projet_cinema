<?php

namespace OC\PlatformBundle\Twig;

use OC\PlatformBundle\Antispam\OCAntispam;

class AntispamExtension extends \Twig_Extension
{
  /**
   * @var OCAntispam
   */
  private $ocAntispam;

  public function __construct(OCAntispam $ocAntispam)
  {
    $this->ocAntispam = $ocAntispam;
  }

  public function checkIfArgumentIsSpamm($text)
  {
    return $this->ocAntispam->isSpam($text);
  }

  // Twig execute cette méthode afin de savoir quelles fonctions de NOTRE service il doit utiliser
  public function getFunctions()
  {
    return [new \Twig_SimpleFunction('checkIfSpamm', [$this, 'checkIfArgumentIsSpamm']) ];
  }

  // Obligatoire pour identifier l'extension Twig
  public function getName()
  {
    return 'OCAntispam';
  }
}
