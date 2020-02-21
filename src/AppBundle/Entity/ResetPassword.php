<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ResetPassword
 *
 * @ORM\Table(name="reset_password")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResetPasswordRepository")
 */
class ResetPassword
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="email", type="string", length=60)
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(name="hashKey", type="string", length=255)
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
     * @return ResetPassword
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
     * @return ResetPassword
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
