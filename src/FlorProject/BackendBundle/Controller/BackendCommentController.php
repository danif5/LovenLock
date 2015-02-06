<?php

namespace FlorProject\BackendBundle\Controller;

use FlorProject\BackendBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FlorProject\BackendBundle\Entity\Blog;
use FlorProject\BackendBundle\Form\Type\BlogFormType;

/**
 * BackendComment controller.
 *
 */
class BackendCommentController extends Controller
{
    public function deleteAction($id)
    {

        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $comment = $em->getRepository('FlorProjectBackendBundle:Comment')->find($id);
            if ($comment) {
                $em->remove($comment);
                $em->flush();
                //todo: Eliminado
                $this->get('session')->getFlashBag()->add('error', "El cmentario fue eliminado");
            }
        }
        $url = $this->get('Request')->server->get('HTTP_REFERER');
        return $this->redirect($url);
    }

}
