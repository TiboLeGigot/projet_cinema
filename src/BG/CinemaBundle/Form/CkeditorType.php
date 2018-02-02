<?php

namespace BG\CinemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CkeditorType extends AbstractType
{
  public function configureOptions(OptionsResolver $resolver)
  {
    // On ajout la classe CSS
    $resolver->setDefaults(['attr' =>
      ['class' => 'ckeditor']
    ]);
  }

  public function getParent()
  {
    return TextareaType::class;
  }
}
