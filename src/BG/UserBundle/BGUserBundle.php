<?php

namespace BG\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BGUserBundle extends Bundle
{
  public function getParent()
  {
    return 'FOSUserBundle';
  }
}
