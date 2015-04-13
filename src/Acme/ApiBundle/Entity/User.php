<?php

namespace Acme\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
<<<<<<< HEAD
use JMS\Serializer\Annotation as JMS;
=======
>>>>>>> Add ApiBundle

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
<<<<<<< HEAD
 * @JMS\XmlRoot("user")
=======
>>>>>>> Add ApiBundle
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
<<<<<<< HEAD
     * @JMS\XmlAttribute
=======
>>>>>>> Add ApiBundle
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthDate;

<<<<<<< HEAD
    /**
     * @ORM\Column(type="datetime")
     * @JMS\Exclude
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

=======
>>>>>>> Add ApiBundle
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getBirthDate()
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTime $birthDate)
    {
        $this->birthDate = $birthDate;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }
<<<<<<< HEAD

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
=======
>>>>>>> Add ApiBundle
}
