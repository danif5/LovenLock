<?php

namespace FlorProject\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FlorProjectUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
