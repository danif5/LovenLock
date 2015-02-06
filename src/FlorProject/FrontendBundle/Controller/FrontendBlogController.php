<?php

    namespace FlorProject\FrontendBundle\Controller;

    use FlorProject\BackendBundle\Entity\Blog;
    use FlorProject\BackendBundle\Entity\Comment;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use FlorProject\BackendBundle\Form\Type\CommentType;

    class FrontendBlogController extends Controller
    {
        public function indexAction()
        {
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('FlorProjectBackendBundle:Blog')
                ->findBy(array(), array('creationDate' => 'DESC'));

            return $this->render('FlorProjectFrontendBundle:Blog:index.html.twig', array(
                'entities' => $entities,
            ));
        }

        public function showAction($id)
        {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('FlorProjectBackendBundle:Blog')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Blog entity.');
            }

            $comment = new Comment();
            $comment->setBlog($entity);
            $form = $this->createCommentForm($comment);

            return $this->render('FlorProjectFrontendBundle:Blog:show.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
        }

        private function createCommentForm(Comment $entity)
        {
            $form = $this->createForm(new CommentType(), $entity, array(
                'action' => $this->generateUrl('flor_project_frontend_comment_create'),
                'method' => 'POST',
            ));

            //$form->add('submit', 'submit', array('label' => 'Create'));

            return $form;
        }

        public function createCommentAction(Request $request)
        {
            $entity = new Comment();
            $form   = $this->createCommentForm($entity);
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $blogId = $form->getData()->getBlog();
            if($blogId){
                $blog = $em->getRepository('FlorProjectBackendBundle:Blog')->find($blogId);
                $entity->setBlog($blog);
            }

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                //return $this->redirect($this->generateUrl('backend_blog_show', array('id' => $entity->getId())));
            }else{
                //todo: Error
                $this->get('session')->getFlashBag()->add('error', "Debe llenar correctamente los campos");

            }
            $url = $this->get('Request')->server->get('HTTP_REFERER');
            return $this->redirect($url);
        }
    }
