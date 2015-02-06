<?php

    namespace FlorProject\BackendBundle\Controller;

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use FlorProject\BackendBundle\Entity\Blog;
    use FlorProject\BackendBundle\Form\Type\BlogFormType;

    /**
     * BackendBlog controller.
     *
     */
    class BackendBlogController extends Controller
    {

        /**
         * Lists all Blog entities.
         *
         */
        public function indexAction()
        {
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('FlorProjectBackendBundle:Blog')
                ->findBy(array(), array('creationDate' => 'DESC'));

            return $this->render('FlorProjectBackendBundle:Blog:index.html.twig', array(
                'entities' => $entities,
            ));
        }

        /**
         * Creates a new Blog entity.
         *
         */
        public function createAction(Request $request)
        {
            $entity = new Blog();
            $form   = $this->createCreateForm($entity);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('backend_blog_show', array('id' => $entity->getId())));
            }

            return $this->render('FlorProjectBackendBundle:Blog:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
        }

        /**
         * Creates a form to create a Blog entity.
         *
         * @param Blog $entity The entity
         *
         * @return \Symfony\Component\Form\Form The form
         */
        private function createCreateForm(Blog $entity)
        {
            $form = $this->createForm(new BlogFormType(), $entity, array(
                'action' => $this->generateUrl('backend_blog_create'),
                'method' => 'POST',
            ));

            //$form->add('submit', 'submit', array('label' => 'Create'));

            return $form;
        }

        /**
         * Displays a form to create a new Blog entity.
         *
         */
        public function newAction()
        {
            $entity = new Blog();
            $form   = $this->createCreateForm($entity);

            return $this->render('FlorProjectBackendBundle:Blog:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
        }

        /**
         * Finds and displays a Blog entity.
         *
         */
        public function showAction($id)
        {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('FlorProjectBackendBundle:Blog')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Blog entity.');
            }

            $deleteForm = $this->createDeleteForm($id);

            return $this->render('FlorProjectBackendBundle:Blog:show.html.twig', array(
                'entity'      => $entity,
                'delete_form' => $deleteForm->createView(),
            ));
        }

        /**
         * Displays a form to edit an existing Blog entity.
         *
         */
        public function editAction($id)
        {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('FlorProjectBackendBundle:Blog')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Blog entity.');
            }

            $editForm   = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($id);

            return $this->render('FlorProjectBackendBundle:Blog:edit.html.twig', array(
                'entity'      => $entity,
                //'edit_form'   => $editForm->createView(),
                'form'        => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }

        /**
         * Creates a form to edit a Blog entity.
         *
         * @param Blog $entity The entity
         *
         * @return \Symfony\Component\Form\Form The form
         */
        private function createEditForm(Blog $entity)
        {
            $form = $this->createForm(new BlogFormType(), $entity, array(
                'action' => $this->generateUrl('backend_blog_update', array('id' => $entity->getId())),
                'method' => 'POST',
            ));

            //$form->add('submit', 'submit', array('label' => 'Editar'));

            return $form;
        }

        /**
         * Edits an existing Blog entity.
         *
         */
        public function updateAction(Request $request, $id)
        {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('FlorProjectBackendBundle:Blog')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Blog entity.');
            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm   = $this->createEditForm($entity);
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $em->flush();

                return $this->redirect($this->generateUrl('backend_blog_show', array('id' => $id)));
            }

            return $this->render('FlorProjectBackendBundle:Blog:edit.html.twig', array(
                'entity'      => $entity,
                'form'        => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }

        /**
         * Deletes a Blog entity.
         *
         */
        public function deleteAction(Request $request, $id)
        {
            $id   = $request->get('blog_id');
            $em   = $this->getDoctrine()->getManager();
            $blog = $em->getRepository('FlorProjectBackendBundle:Blog')->find($id);
            if ($blog != null) {
                try {
                    $em->remove($blog);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('message', "Artículo eliminado con éxito");
                } catch (\Exception $ex) {
                    $this->get('session')->getFlashBag()->add('message', "No se puede eliminar un artículo referenciado");
                }
            } else
                $this->get('session')->getFlashBag()->add('error', "No se ha encontrado el artículo solicitado");

            return $this->redirect($this->generateUrl('backend_blog'));
        }

        /**
         * Deletes a Blog entity confimration.
         *
         */
        public function deleteConfirmationAction($id)
        {
            $blog_post = $this->getDoctrine()->getManager()->getRepository('FlorProjectBackendBundle:Blog')->find($id);
            if ($blog_post != null) {
                return $this->render('FlorProjectBackendBundle:Blog:delete.html.twig', array('entity' => $blog_post));
            }

            $this->get('session')->getFlashBag()->add('error', "No se encontrado el artículo solicitado");
            return $this->redirect($this->generateUrl('backend_blog'));
//backend_blog_delete_confirm
            /* $form = $this->createDeleteForm($id);
             $form->handleRequest($request);

             if ($form->isValid()) {
                 $em     = $this->getDoctrine()->getManager();
                 $entity = $em->getRepository('FlorProjectBackendBundle:Blog')->find($id);

                 if (!$entity) {
                     throw $this->createNotFoundException('Unable to find Blog entity.');
                 }

                 $em->remove($entity);
                 $em->flush();
             }

             return $this->redirect($this->generateUrl('blog'));*/
        }

        /**
         * Creates a form to delete a Blog entity by id.
         *
         * @param mixed $id The entity id
         *
         * @return \Symfony\Component\Form\Form The form
         */
        private function createDeleteForm($id)
        {
            return $this->createFormBuilder()
                ->setAction($this->generateUrl('backend_blog_delete', array('id' => $id)))
                ->setMethod('POST')
                ->add('submit', 'submit', array('label' => 'Eliminar'))
                ->getForm();
        }
    }
