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
  const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
  const ROLE_ADMIN = 'ROLE_ADMIN';
  const ROLE_USER = 'ROLE_USER';

/**
 * @var integer
 *
 * @ORM\Column(name="id", type="integer")
 * @ORM\Id
 * @ORM\GeneratedValue(strategy="AUTO")
 */
protected $id;

/**
 * @var
 *
 * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
 */
protected $firstname;

/**
 * @var
 *
 * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
 */
protected $lastname;

/**
 * @var
 *
 * @ORM\Column(name="address", type="text", nullable=true, nullable=true)
 */
protected $address;

/**
 * @var
 *
 * @ORM\Column(name="zip_code", type="string", length=255, nullable=true)
 */
protected $zipCode;

/**
 * @var
 *
 * @ORM\Column(name="city", type="string", length=255, nullable=true)
 */
protected $city;

public function __construct()
{
    parent::__construct();
}

/**
 * @param mixed $firstname
 */
public function setFirstname($firstname)
{
    $this->firstname = $firstname;
}

/**
 * @return mixed
 */
public function getFirstname()
{
    return $this->firstname;
}

/**
 * @param mixed $lastname
 */
public function setLastname($lastname)
{
    $this->lastname = $lastname;
}

/**
 * @return mixed
 */
public function getLastname()
{
    return $this->lastname;
}



/**
 * @param mixed $address
 */
public function setAddress($address)
{
    $this->address = $address;
}

/**
 * @return mixed
 */
public function getAddress()
{
    return $this->address;
}

/**
 * @param mixed $city
 */
public function setCity($city)
{
    $this->city = $city;
}

/**
 * @return mixed
 */
public function getCity()
{
    return $this->city;
}


/**
 * @param int $id
 */
public function setId($id)
{
    $this->id = $id;
}

/**
 * @return int
 */
public function getId()
{
    return $this->id;
}

/**
 * @param mixed $zipCode
 */
public function setZipCode($zipCode)
{
    $this->zipCode = $zipCode;
}

/**
 * @return mixed
 */
public function getZipCode()
{
    return $this->zipCode;
}

}
