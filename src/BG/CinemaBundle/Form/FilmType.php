<?php

namespace BG\CinemaBundle\Form;

use BG\CinemaBundle\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $pattern = 'D%';

    $builder
      ->add('date',       DateTimeType::class)
      ->add('title',      TextType::class)
      ->add('author',     TextType::class)
      ->add('content',    CkeditorType::class)
      ->add('image',      ImageType::class)
      ->add('categories', EntityType::class, [
        'class'         => 'BGCinemaBundle:Category',
        'choice_label'  => 'name',
        'multiple'      => true,
        'query_builder' => function(CategoryRepository $repository) use($pattern) {
          return $repository->getLikeQueryBuilder($pattern);
        }
      ])
      ->add('save',        SubmitType::class)
    ;

    $builder->addEventListener(
      FormEvents::PRE_SET_DATA,    // 1er argument : L'évènement qui nous intéresse : ici, PRE_SET_DATA
      function(FormEvent $event) { // 2e argument : La fonction à exécuter lorsque l'évènement est déclenché
        // On récupère notre objet Film sous-jacent
        $film = $event->getData();

        if (null === $film) {
          return;
        }

        if (!$film->getPublished() || null === $film->getId()) {
          $event->getForm()->add('published', CheckboxType::class, array('required' => false));
        } else {
          $event->getForm()->remove('published');
        }
      }
    );
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'BG\CinemaBundle\Entity\Film'
    ));
  }
}
