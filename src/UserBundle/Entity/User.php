<?php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 *
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float", options={"default" = 0})
     * @Assert\NotBlank()
     */
    private $rating;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registered_at", type="datetime", options={"default" = 0})
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     */
    private $registeredAt;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Set rating
     *
     * @param float $rating
     *
     * @return User
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set registeredAt
     *
     * @param \DateTime $registeredAt
     *
     * @return User
     */
    public function setRegisteredAt($registeredAt)
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    /**
     * Get registeredAt
     *
     * @return \DateTime
     */
    public function getRegisteredAt()
    {
        return $this->registeredAt;
    }

    /**
     * @ORM\PrePersist()
     *
     */

    public function prePersist()
    {
        $this->registeredAt = new \DateTime;
    }
}

