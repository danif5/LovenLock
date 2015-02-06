<?php
/**
 * Created by PhpStorm.
 * User: Gandalf_X
 * Date: 11/11/2014
 * Time: 01:46 PM
 */

namespace FlorProject\BackendBundle\Controller;
use FOS\UserBundle\Controller\SecurityController as BaseController;

/**
 * {@inheritdoc}
 */
class BackendSecurityController extends BaseController{

    /**
     * {@inheritdoc}
     */
    protected function renderLogin(array $data)
    {
        $requestAttributes = $this->container->get('request')->attributes;

        if ('admin_login' === $requestAttributes->get('_route')) {
            $template = sprintf('FlorProjectBackendBundle:User:login.html.twig');
        } else {
            $template = sprintf('FOSUserBundle:Security:login.html.twig');
        }

        return $this->container->get('templating')->renderResponse($template, $data);
    }

} 