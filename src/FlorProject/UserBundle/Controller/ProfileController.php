<?php

namespace FlorProject\UserBundle\Controller;


use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends BaseController
{
    /**
     * Show the user
     */
    public function showAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        /*if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }*/

        return $this->container->get('templating')
            ->renderResponse('FOSUserBundle:Profile:show.html.' . $this->container->getParameter('fos_user.template.engine'),
                array(
                    'user' => $user,
                    'param1' => '',
                    'param2' => '',
                    'param3' => '',
                    'param4' => '',
                    'param5' => 'active',
                )
            );
    }

    /**
     * Redirect the user to edit Sabrus preferences since it's not allowed to change username/email at the moment.
     */
    public function editAction()
    {
        return new RedirectResponse($this->container->get('router')->generate('register'));
    }
}
