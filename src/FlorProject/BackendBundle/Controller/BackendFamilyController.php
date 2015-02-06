<?php

namespace FlorProject\BackendBundle\Controller;

use FlorProject\BackendBundle\Entity\Family;
use FlorProject\BackendBundle\Form\Type\FamilyFormType;
use FlorProject\BackendBundle\Form\Type\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class BackendFamilyController extends Controller
{

    /**
     * Lists all entities.
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('FlorProjectBackendBundle:Family:list.html.twig');
    }

    public function newAction()
    {
        $form = $this->createForm(new FamilyFormType());
        return $this->render('FlorProjectBackendBundle:Family:new.html.twig', array(
                'form' => $form->createView()
            ));
    }

    public function createAction(Request $request)
    {
        $family = new Family();
        $form = $this->createForm(new FamilyFormType(), $family);
        $form->handleRequest($request);


        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($family);
            $em->flush();
            $this->get('session')->getFlashBag()->add('message', "Familia de regalos creada con éxito");
            return $this->redirect($this->generateUrl('backend_family_list'));
        }
        $this->get('session')->getFlashBag()->add('error', "Debe llenar correctamente los campos");
        return $this->render('FlorProjectBackendBundle:Family:new.html.twig', array(
                'form' => $form->createView()
            ));

    }

    public function editAction($id)
    {
        $family = $this->getDoctrine()->getManager()->getRepository('FlorProjectBackendBundle:Family')->find($id);
        if ($family != null)
        {
            $form = $this->createForm(new FamilyFormType(), $family);
            return $this->render('FlorProjectBackendBundle:Family:edit.html.twig', array(
                    'form' => $form->createView(),
                    'family' => $family
                ));
        }

        $this->get('session')->getFlashBag()->add('error', "No se encontrado la familia solicitada");

        return $this->redirect($this->generateUrl('backend_user_list'));

    }

    public function deleteAction($id)
    {
        $family = $this->getDoctrine()->getManager()->getRepository('FlorProjectBackendBundle:Family')->find($id);
        if ($family != null)
        {
            return $this->render('FlorProjectBackendBundle:Family:delete.html.twig', array(
                    'family' => $family
                ));
        }

        $this->get('session')->getFlashBag()->add('error', "No se encontrado la familia solicitada");
        return $this->redirect($this->generateUrl('backend_user_list'));

    }

    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $family = $em->getRepository('FlorProjectBackendBundle:Family')->find($request->get('family_id'));
        if ($family != null)
        {
            $form = $this->createForm(new FamilyFormType(), $family);
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $em->merge($family);
                $em->flush();
                $this->get('session')->getFlashBag()->add('message', "Familia actualizada con éxito");
                return $this->redirect($this->generateUrl('backend_family_list'));
            }
            $this->get('session')->getFlashBag()->add('error', "Debe llenar correctamente los campos");
        }
        else
            $this->get('session')->getFlashBag()->add('error', "No se encontrado la familia solicitada");

        return $this->render('FlorProjectBackendBundle:Family:edit.html.twig', array(
                'form' => $form->createView(),
                'family' => $family
            ));
    }

    public function deleteConfirmedAction(Request $request)
    {
        $id = $request->get('family_id');
        $em = $this->getDoctrine()->getManager();
        $family = $em->getRepository('FlorProjectBackendBundle:Family')->find($id);
        if ($family != null) {
            try
            {
                $em->remove($family);
                $em->flush();
                $this->get('session')->getFlashBag()->add('message', "Familia eliminada con éxito");
            }
            catch (\Exception $ex)
            {
                $this->get('session')->getFlashBag()->add('message', "No se pueden eliminar familias asociadas con regalos");
            }

        }
        else
            $this->get('session')->getFlashBag()->add('error', "No se ha encontrado la familia solicitada");

        return $this->redirect($this->generateUrl('backend_family_list'));
    }

    public function getTypeName($typeCode)
    {
        switch($typeCode)
        {
            case 1:
                return "Candado";
            case 2:
                return "Flor";
            case 3:
                return "Caja";
            default:
                return "Desconocido";
        }

    }

    public function listAjaxAction(Request $request)
    {


        $pageNumber = intval($request->get('page'));
        $pageLength = intval($request->get('length'));
        $echo = intval($request->get('draw'));

        $filterName = '%'.$request->get('filter_name') . "%";
        $filterGiftType = '%'.$request->get('filter_gift_type') . "%";


        $em = $this->getDoctrine()->getManager();

        $totalRecord = $em->createQuery(
            'SELECT count(f.id) FROM FlorProjectBackendBundle:Family f WHERE f.familyName LIKE :filterName
             AND f.giftType LIKE :filterGiftType ORDER BY f.id DESC'
        )->setParameter('filterName', $filterName)
         ->setParameter('filterGiftType', $filterGiftType)
         ->getSingleScalarResult();

        $queryObject = $em->createQuery(
            'SELECT f FROM FlorProjectBackendBundle:Family f WHERE f.familyName LIKE :filterName
             AND f.giftType LIKE :filterGiftType ORDER BY f.id DESC'
        )->setParameter('filterName', $filterName)
         ->setParameter('filterGiftType', $filterGiftType)
         ->setFirstResult($pageNumber * $pageLength)
         ->setMaxResults($pageLength);


        $families = $queryObject->getResult();



        $data = array();
        foreach($families as $actualFamily)
        {
            $controls = '<a href="/admin/family/edit/'. $actualFamily->getId() .'" class="btn btn-xs default"><i class="fa fa-search"></i></a>' . '<a href="/admin/family/delete/'.$actualFamily->getId().'" class="btn btn-xs default"><i class="fa fa-times"></i></a>';

            $data[] = array(
                $actualFamily->getId(),
                $this->getTypeName($actualFamily->getGiftType()),
                $actualFamily->getFamilyName(),
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
