<?php

namespace Acme\ApiBundle\Entity;

use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\XmlRoot("users")
 */
class UserCollection
{
    /**
     * @JMS\XmlList(inline=true, entry="user")
     */
    private $users;

    public function __construct($users)
    {
        $this->users = iterator_to_array($users);
    }

    public function getUsers()
    {
        return $this->users;
    }
}
