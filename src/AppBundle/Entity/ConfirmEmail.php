<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConfirmEmail
 *
 * @ORM\Table(name="confirm_email")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConfirmEmailRepository")
 */
class ConfirmEmail
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="hashKey", type="string", length=255, unique=true)
     */
    private $hashKey;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return ConfirmEmail
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set hashKey
     *
     * @param string $hashKey
     *
     * @return ConfirmEmail
     */
    public function setHashKey($hashKey)
    {
        $this->hashKey = $hashKey;

        return $this;
    }

    /**
     * Get hashKey
     *
     * @return string
     */
    public function getHashKey()
    {
        return $this->hashKey;
    }
}
