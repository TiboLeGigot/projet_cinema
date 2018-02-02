<?php
namespace BG\PlatformBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Antiflood extends Constraint
{
  public $message = "Vous avez déjà posté un message il y a moins de 15s, merci d'attendre un peu.";

  public function validateBy()
  {
    return 'bg_platform_antiflood'; // On fait appel à l'alias du service
  }
}
