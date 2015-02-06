<?php

namespace FlorProject\UserBundle\Form\Model;

use FlorProject\BackendBundle\Entity\User;

/**
 * RegistrationFormClass
 */
class RegistrationFormClass
{
    /**
     * @var UserId
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank(message="Por favor entre su primer nombre", groups={"Registration", "Profile"})
     */
    private $firstName;

    /**
     * @var string
     * @Assert\NotBlank(message="Por favor entre su apellido", groups={"Registration", "Profile"})
     */
    private $lastName;

    /**
     * @var string
     * @Assert\NotBlank(message="Por favor entre su país", groups={"Registration", "Profile"})
     */
    private $country;

    /**
     * @var string
     * @Assert\NotBlank(message="Por favor entre su fecha de nacimiento", groups={"Registration", "Profile"})
     */
    private $birthDate;


    /**
     * @var string
     * @Assert\NotBlank(message="Por favor entre su género", groups={"Registration", "Profile"})
     */
    private $gender;



    /**
     * @var file
     *
     */
    private $file;

    /**
     * Set firstName
     *
     * @param  string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Returns the UserId.
     *
     * @return UserId
     */
    public function getId()
    {
        return new UserId($this->id);
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param  string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set country
     *
     * @param  string $country
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }


    /**
     * Set country
     *
     * @param  string $country
     * @return User
     */
    public function setBirth($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Set birthDate
     *
     * @param string $birthDate
     * @return User
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return string
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }


    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }


    /**
     * @return file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param file $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }


    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
        $this->country = $user->getCountry();
        $this->birthDate = $user->getBirthDate();
        $this->gender = $user->getGender();
        $this->file = $user->getFile();
    }
}
