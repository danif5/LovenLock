<?php
/**
 * Created by PhpStorm.
 * User: Karel
 * Date: 9/17/14
 * Time: 1:58 PM
 */

namespace FlorProject\UserBundle\Form\Model;

class FacebookLogin
{
    protected $name;

    protected $email;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}
