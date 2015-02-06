<?php
/**
 * Created by PhpStorm.
 * User: Karel
 * Date: 9/18/14
 * Time: 5:00 PM
 */

namespace FlorProject\UserBundle\Controller;

use FlorProject\BackendBundle\Entity\User;
use FlorProject\UserBundle\Form\Model\FacebookLogin;
use FlorProject\UserBundle\Form\Type\FacebookLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class OAuthController extends Controller
{
    public function facebookLoginAction(Request $request)
    {
        if ($request->getMethod() === "GET") {
            $fbLoginForm = $this->createForm(
                new FacebookLoginType(),
                new FacebookLogin(),
                array('action' => $this->generateUrl('facebook_login'))
            );

            return $this->render(
                'FlorProjectUserBundle:OAuth:facebookLogin.html.twig',
                array('fbLoginForm' => $fbLoginForm->createView())
            );
        } else {
            if ($request->getMethod() === "POST") {

                $form = $this->createForm(new FacebookLoginType(), new FacebookLogin());
                $form->handleRequest($request);

                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $userRepository = $em->getRepository('SabrusUserBundle:User');

                    $fbLoginData = $form->getData();
                    $user = $userRepository->findByEmail($fbLoginData->getEmail());

                    $firstTimeUser = false;
                    if ($user == null) {
                        //If first-time-user using facebook, we should add him to db
                        $user = new User();
                        $user->setEmail($fbLoginData->getEmail())
                            ->setPassword("")
                            ->setEmailCanonical(strtolower($fbLoginData->getEmail()))
                            ->setFirstName($fbLoginData->getName())
                            ->setRoles(
                                array("ROLE_USER", "ROLE_FBUSER") //add a custom role maybe to identify fb users?
                            )->setEnabled(true); //enable directly because this is a confirmed user email from facebook.

                        $em->persist($user);
                        $em->flush();

                        $firstTimeUser = true;
                    }

                    //authenticate the user
                    $providerKey = $this->container->getParameter('fos_user.firewall_name');
                    $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
                    $this->get("security.context")->setToken($token);
                    $this->get('session')->set('_security_main', serialize($token));

                    //redirect to welcome page after first time.
                    if ($firstTimeUser) {
                        return $this->redirect($this->generateUrl('welcome_facebook_user', array("email" => $fbLoginData->getEmail())));
                    }
                }
            }
        }

        return $this->redirect($this->generateUrl("home"));
    }

    public function welcomeFacebookUserAction()
    {
        $user = $this->getUser();
        if ($user) {
            return $this->render("SabrusUserBundle:OAuth:welcomeFacebookUser.html.twig", array("fbUser" => $user, 'param1' => 'active', 'param2' => '', 'param3' => '', 'param4' => '', 'opacity' => 1));
        }

        throw new AccessDeniedException();
    }

}
