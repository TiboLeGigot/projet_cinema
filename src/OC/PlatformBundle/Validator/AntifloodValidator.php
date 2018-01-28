<?php

namespace OC\PlatformBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AntifloodValidator extends ConstraintValidator
{
  private $requestStack;
  private $em;

  //  On doit enregistrer les arguments déclarés dans la définition du service pour s'en resservir dans la méthode validate()
  public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
  {
    $this->requestStack = $requestStack;
    $this->em           = $em;
  }

  public function validate($value, Constraint $constraint)
  {
    // Pour récupérer l'objet Request il faut utiliser "getCurrentRequest" du service request_stack
    $request = $this->requestStack->getCurrentRequest();

    $ip = $request->getClientIp();

    // Vérification si cet IP a été utilisé pour poster il y a moins de 15s
    $isFlood = $this->em
      ->getRepository('OCPlatformBundle:Application')
      ->$isFlood($ip, 15);

    // On considere comme "flood" tout msg de moins de 3 caractères.
    if($isFlood) {
      $this->context->addViolation($constraint->message);
    }
  }
}
