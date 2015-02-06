<?php

namespace FlorProject\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FlorProject\BackendBundle\Form\Type\MessageFormType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('FlorProjectBackendBundle:Blog');
        //->findBy(array(), array('creationDate' => 'DESC'));

        $query = $repository->createQueryBuilder('b')
            ->orderBy('b.creationDate', 'DESC')
            ->setMaxResults(3)
            ->getQuery();
        $blogs = $query->getResult();

        return $this->render('FlorProjectFrontendBundle:Default:index.html.twig', array(
            'blogs' => $blogs
        ));
    }

    public function sendMessageAction(Request $request)
    {
        $entity = array();
        $form = $this->createMessageForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $admin = $em->getRepository('FlorProjectBackendBundle:User')->find(1);
            $adminEmail = $admin->getEmail();
            //send email
            $data = $form->getData();
            $sendEmail = true;
            if ($sendEmail) {
                $mailer = $this->get('mailer');
                $contentType = 'text/html';
                $message = $mailer->createMessage()
                    ->setSubject($data['subject'])
                    ->setFrom($data['email'])
                    ->setTo($adminEmail)
                    ->setContentType($contentType)
                    ->setBody(
                        $this->renderView(
                            'FlorProjectFrontendBundle:Common:email.html.twig',
                            array('data' => $data)
                        )
                    );
                $mailer->send($message);
            }

        } else {
            //todo: Error
            $this->get('session')->getFlashBag()->add('error', "Debe llenar correctamente los campos");

        }
        $url = $this->get('Request')->server->get('HTTP_REFERER');
        return $this->redirect($url);
    }

    private function createMessageForm($entity)
    {
        $form = $this->createForm(new MessageFormType(), $entity, array(
            'action' => $this->generateUrl('sendMessage'),
            'method' => 'POST',
            'csrf_protection' => false,
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }
}
