<?php

namespace FlorProject\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Family
 *
 * @ORM\Table(name="family")
 * @ORM\Entity
 */
class Family
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="giftType", type="integer", options={"default" = 1})
     */
    private $giftType;

    /**
     * @var string
     *
     * @ORM\Column(name="familyName", type="string", length=20)
     */
    private $familyName;


    function __construct()
    {
    }

    /**
     * @return string
     */
    public function getGiftType()
    {
        return $this->giftType;
    }

    /**
     * @param string $giftType
     */
    public function setGiftType($giftType)
    {
        $this->giftType = $giftType;
    }




    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $familyName
     * @return Family
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }
}
