<?php

namespace BG\CinemaBundle\Controller;

use BG\CinemaBundle\Entity\Film;
use BG\CinemaBundle\Form\FilmEditType;
use BG\CinemaBundle\Form\FilmType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
// use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class FilmController extends Controller
{
  public function indexAction($page)
  {

    // Ici je fixe le nombre d'annonces par page à 3
    // Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
    $nbPerPage = 3;

    // On récupère notre objet Paginator
    $listFilms = $this->getDoctrine()
      ->getManager()
      ->getRepository('BGCinemaBundle:Film')
      ->getFilms($page, $nbPerPage)
    ;

    // On calcule le nombre total de pages grâce au count($listFilms) qui retourne le nombre total d'annonces
    $nbPages = ceil(count($listFilms) / $nbPerPage);

    // Si la page n'existe pas, on retourne une 404
    if ($page > $nbPages && $page != 1) {
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

    // On donne toutes les informations nécessaires à la vue
    return $this->render('BGCinemaBundle:Film:index.html.twig', array(
      'listFilms'   => $listFilms,
      'nbPages'     => $nbPages,
      'page'        => $page,
    ));
  }

  /**
   * @ParamConverter("film", options={"mapping": {"film_id": "id"}})
   */
  public function viewAction(Film $film)
  {
    $em = $this->getDoctrine()->getManager();

    $id = $film->getId();

    if (null === $film) {
      throw new NotFoundHttpException("Le film n° ".$id." n'existe pas.");
    }

    // Récupération de la liste des candidatures de l'annonce
    // $listApplications = $em
    //   ->getRepository('BGCinemaBundle:Application')
    //   ->findBy(array('film' => $film))
    // ;

    // Récupération des FilmSkill de l'annonce
    // $listFilmSkills = $em
    //   ->getRepository('BGCinemaBundle:FilmSkill')
    //   ->findBy(array('film' => $film))
    // ;

    return $this->render('BGCinemaBundle:Film:view.html.twig', array(
      'film'             => $film
    ));
  }

  /**
   * @Security("has_role('ROLE_AUTHOR')")
   */

  public function addAction(Request $request)
  {
    $film = new Film();
    // $form = $this->get('form.factory')->create(FilmType::class, $film);
    $form = $this->createForm(FilmType::class, $film);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($film);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      return $this->redirectToRoute('bg_cinema_view', ['film_id' => $film->getId()] );
    }

    return $this->render('BGCinemaBundle:Film:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function editAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $film = $em->getRepository('BGCinemaBundle:Film')->find($id);

    if (null === $film) {
      throw new NotFoundHttpException("Le film n° ".$id." n'existe pas.");
    }

    $form = $this->get('form.factory')->create(FilmEditType::class, $film);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      // Inutile de persister ici, Doctrine connait déjà notre annonce
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

      return $this->redirectToRoute('bg_cinema_view', ['film_id' => $film->getId()] ) ;
    }

    return $this->render('BGCinemaBundle:Film:edit.html.twig', array(
      'film' => $film,
      'form'   => $form->createView(),
    ));
  }

  public function deleteAction(Request $request, $id)
  {
    $em = $this->getDoctrine()->getManager();

    $film = $em->getRepository('BGCinemaBundle:Film')->find($id);

    if (null === $film) {
      throw new NotFoundHttpException("Le film n° ".$id." n'existe pas.");
    }

    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression d'annonce contre cette faille
    $form = $this->get('form.factory')->create();

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em->remove($film);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

      return $this->redirectToRoute('bg_cinema_home');
    }

    return $this->render('BGCinemaBundle:Film:delete.html.twig', array(
      'film' => $film,
      'form'   => $form->createView(),
    ));
  }

  public function menuAction($limit)
  {
    $em = $this->getDoctrine()->getManager();

    $listFilms = $em->getRepository('BGCinemaBundle:Film')->findBy(
      array(),                 // Pas de critère
      array('date' => 'desc'), // On trie par date décroissante
      $limit,                  // On sélectionne $limit annonces
      0                        // À partir du premier
    );

    return $this->render('BGCinemaBundle:Film:menu.html.twig', array(
      'listFilms' => $listFilms
    ));
  }

  // Méthode facultative pour tester la purge
  public function purgeAction($days, Request $request)
  {
    // On récupère notre service
    $purger = $this->get('bg_cinema.purger.film');

    // On purge les annonces
    $purger->purge($days);

    // On ajoute un message flash arbitraire
    $request->getSession()->getFlashBag()->add('info', 'Les annonces plus vieilles que '.$days.' jours ont été purgées.');

    // On redirige vers la page d'accueil
    return $this->redirectToRoute('bg_cinema_home');
  }

  public function testAction()
  {
    $film = new Film;
    $film->setDate(new \DateTime());
    $film->setTitle('titre de test');
    $film->setAuthor('A');

    $validator = $this->get('validator');
    $listErrors = $validator->validate($film);

    if(count($listErrors) > 0) {
      return new Response((string) $listErrors);
    } else {
      return new Response("l'annonce est validée !");
    }
  }

  /*
   * @ParamConverter("json")
   */
  public function ParamConverterAction($json)
  {
    return new Response(print_r($json, true));
  }

  public function debatsAction()
  {
    return $this->render('BGCinemaBundle:Film:debats.html.twig', []);
  }

  public function americainAction()
  {
    return $this->render('BGCinemaBundle:Film:americain.html.twig', []);
  }

  public function evenementsAction()
  {
    return $this->render('BGCinemaBundle:Film:evenements.html.twig', []);
  }

  public function jeunepublicAction()
  {
    return $this->render('BGCinemaBundle:Film:jeune-public.html.twig', []);
  }

  public function aproposAction()
  {
    return $this->render('BGCinemaBundle:Film:a-propos.html.twig', []);
  }

  public function pratiqueAction()
  {
    return $this->render('BGCinemaBundle:Film:pratique.html.twig', []);
  }

}
