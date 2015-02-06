<?php

namespace FlorProject\BackendBundle\Controller;

use FlorProject\BackendBundle\Form\Type\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BackendUserController extends Controller
{

    /**
     * Lists all entities.
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('FlorProjectBackendBundle:User:list.html.twig');
    }

    public function newAction()
    {
        $form = $this->createForm(new UserFormType('FlorProject\BackendBundle\Entity\User'));
        return $this->render('FlorProjectBackendBundle:User:new.html.twig', array(
                'form' => $form->createView()
            ));
    }

    public function createAction(Request $request)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->createUser();

        $user->setPhoto('photo1.jpg');
        $form = $this->createForm(new UserFormType('FlorProject\BackendBundle\Entity\User'), $user);
        $form->handleRequest($request);
        $roles = array();
        $roles[] = $request->get('role_user');
        $user->setRoles($roles);
        $user->setEnabled(true);
        if ($form->isValid())
        {
            $userManager->updateUser($user, true);
            $this->get('session')->getFlashBag()->add('message', "Usuario creado con exito");
            return $this->redirect($this->generateUrl('backend_user_list'));
        }
        $this->get('session')->getFlashBag()->add('error', "Debe llenar correctamente los campos");
        return $this->render('FlorProjectBackendBundle:User:new.html.twig', array(
                'form' => $form->createView()
            ));

    }

    public function editAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('FlorProjectBackendBundle:User')->find($id);
        if ($user != null)
        {
            $form = $this->createForm(new UserFormType('FlorProject\BackendBundle\Entity\User'), $user);
            return $this->render('FlorProjectBackendBundle:User:edit.html.twig', array(
                    'form' => $form->createView(),
                    'user' => $user
                ));
        }

        $this->get('session')->getFlashBag()->add('error', "No se encontrado el usuario solicitado");

        return $this->redirect($this->generateUrl('backend_user_list'));

    }

    public function deleteAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('FlorProjectBackendBundle:User')->find($id);
        if ($user != null)
        {
            return $this->render('FlorProjectBackendBundle:User:delete.html.twig', array(
                    'user' => $user
                ));
        }

        $this->get('session')->getFlashBag()->add('error', "No se encontrado el usuario solicitado");
        return $this->redirect($this->generateUrl('backend_user_list'));

    }

    public function updateAction(Request $request)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $this->getDoctrine()->getManager()->getRepository('FlorProjectBackendBundle:User')->find($request->get('user_id'));
        if ($user != null)
        {
            $form = $this->createForm(new UserFormType('FlorProject\BackendBundle\Entity\User'), $user);
            $form->handleRequest($request);
            $roles = array();
            $roles[] = $request->get('role_user');
            $user->setRoles($roles);
            $user->setEnabled(true);
            if ($form->isValid())
            {
                $userManager->updateUser($user, true);
                $this->get('session')->getFlashBag()->add('message', "Usuario actualizado con exito");
                return $this->redirect($this->generateUrl('backend_user_list'));
            }
            $this->get('session')->getFlashBag()->add('error', "Debe llenar correctamente los campos");
        }
        else
            $this->get('session')->getFlashBag()->add('error', "No se encontrado el usuario solicitado");

        return $this->render('FlorProjectBackendBundle:User:edit.html.twig', array(
                'form' => $form->createView(),
                'user' => $user
            ));
    }

    public function deleteConfirmedAction(Request $request)
    {
        $id = $request->get('user_id');
        $user = $this->getDoctrine()->getManager()->getRepository('FlorProjectBackendBundle:User')->find($id);
        if ($user != null) {
            $userManager = $userManager = $this->container->get('fos_user.user_manager');
            $user->setEnabled(false);
            $userManager->updateUser($user, true);
            $this->get('session')->getFlashBag()->add('message', "Usuario eliminado con exito");
        }
        else
            $this->get('session')->getFlashBag()->add('error', "No se encontrado el usuario solicitado");

        return $this->redirect($this->generateUrl('backend_user_list'));
    }

    public function listAjaxAction(Request $request)
    {

        $roles = array();
        $roles['ROLE_ADMIN'] = 'Administrador';
        $roles['ROLE_USER'] = 'Usuario';
        $roles['ROLE_TRANSLATOR'] = 'Traductor';


        $pageNumber = intval($request->get('page'));
        $pageLength = intval($request->get('length'));
        $echo = intval($request->get('draw'));

        $filterFirstName = '%'.$request->get('filter_firstname') . "%";
        $filterLastName = '%'.$request->get('filter_lastname') . "%";
        $filterCountry = '%'.$request->get('filter_country') . "%";
        $filterUsername = '%'.$request->get('filter_username') . "%";
        $filterRole = '%'.$request->get('filter_role') . "%";


        $em = $this->getDoctrine()->getManager();

        $totalRecord = $em->createQuery(
            'SELECT count(u.id) FROM FlorProjectBackendBundle:User u WHERE u.enabled = 1 AND u.firstName LIKE :filterFN
             AND u.lastName LIKE :filterLN AND u.country LIKE :filterCountry AND u.username LIKE :filterUsername
             AND u.roles LIKE :filterRole ORDER BY u.id DESC '
        )->setParameter('filterFN', $filterFirstName)
            ->setParameter('filterLN', $filterLastName)
            ->setParameter('filterCountry', $filterCountry)
            ->setParameter('filterUsername', $filterUsername)
            ->setParameter('filterRole', $filterRole)
            ->getSingleScalarResult();

        $queryObject = $em->createQuery(
            'SELECT u FROM FlorProjectBackendBundle:User u WHERE u.enabled = 1 AND u.firstName LIKE :filterFN
             AND u.lastName LIKE :filterLN AND u.country LIKE :filterCountry AND u.username LIKE :filterUsername
             AND u.roles LIKE :filterRole ORDER BY u.id DESC '
        )->setParameter('filterFN', $filterFirstName)
         ->setParameter('filterLN', $filterLastName)
         ->setParameter('filterCountry', $filterCountry)
         ->setParameter('filterUsername', $filterUsername)
         ->setParameter('filterRole', $filterRole)
         ->setFirstResult($pageNumber * $pageLength)
         ->setMaxResults($pageLength);



        $users = $queryObject->getResult();
        $data = array();
        foreach($users as $actualUser)
        {
            $controls = '<a href="/admin/user/edit/'. $actualUser->getId() .'" class="btn btn-xs default"><i class="fa fa-search"></i></a>' . '<a href="/admin/user/delete/'.$actualUser->getId().'" class="btn btn-xs default"><i class="fa fa-times"></i></a>';
            $role = 'Desconocido';

            if ($actualUser->hasRole('ROLE_ADMIN'))
                $role = 'Administrador';
            else if ($actualUser->hasRole('ROLE_USER'))
                $role = 'Usuario';
            else if ($actualUser->hasRole('ROLE_TRANSLATOR'))
                $role = 'Traductor';
            $data[] = array(
                $actualUser->getId(),
                $actualUser->getFirstName(),
                $actualUser->getLastName(),
                $actualUser->getCountry(),
                $actualUser->getUsername(),
                $role,
                $controls
            );
        }

        $response1 = array();
        $response1["data"] = $data;
        $response1["draw"] = $echo;
        $response1["recordsTotal"] = $totalRecord;
        $response1["recordsFiltered"] = $totalRecord;

        return JsonResponse::create($response1);



    }




}
