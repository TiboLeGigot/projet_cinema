<?php

namespace BG\CinemaBundle\Beta;

use Symfony\Component\HttpFoundation\Response;

class BetaHTMLAdder
{
  public function addBeta(Response $response, $remainingDays)
  {
    $content = $response->getContent();

    $html = '<div style="background: orange; width: 100%; text-align: center; padding: 0.5em;">Finir avant J-'.(int) $remainingDays.' !</div>';

    $content = str_replace('<body>', '<body>'.$html, $content);

    $response->setContent($content);

    return $response;
  }
}
