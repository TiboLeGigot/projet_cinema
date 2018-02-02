<?php

namespace BG\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
/**
 * User
 *
 * @ORM\Table(name="bg_user")
 * @ORM\Entity(repositoryClass="BG\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;
}
