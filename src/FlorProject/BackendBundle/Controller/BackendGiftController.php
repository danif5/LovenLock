<?php

namespace FlorProject\BackendBundle\Controller;

use FlorProject\BackendBundle\Entity\Gift;
use FlorProject\BackendBundle\Form\Type\GiftFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BackendGiftController extends Controller
{

    /**
     * Lists all entities.
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('FlorProjectBackendBundle:Gift:list.html.twig');
    }

    public function newAction()
    {
        $form = $this->createForm(new GiftFormType());
        return $this->render('FlorProjectBackendBundle:Gift:new.html.twig', array(
                'form' => $form->createView()
            ));
    }

    public function createAction(Request $request)
    {
        $gift = new Gift();
        $form = $this->createForm(new GiftFormType(), $gift);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gift);
            $em->flush();
            $this->get('session')->getFlashBag()->add('message', "Regalo creado con éxito");
            return $this->redirect($this->generateUrl('backend_gift_list'));
        }
        $this->get('session')->getFlashBag()->add('error', "Debe llenar correctamente los campos");
        return $this->render('FlorProjectBackendBundle:User:new.html.twig', array(
                'form' => $form->createView()
            ));

    }

    public function editAction($id)
    {
        $gift = $this->getDoctrine()->getManager()->getRepository('FlorProjectBackendBundle:Gift')->find($id);
        if ($gift != null)
        {
            $form = $this->createForm(new GiftFormType(), $gift);
            return $this->render('FlorProjectBackendBundle:Gift:edit.html.twig', array(
                    'form' => $form->createView(),
                    'gift' => $gift
                ));
        }

        $this->get('session')->getFlashBag()->add('error', "No se encontrado el regalo solicitado");

        return $this->redirect($this->generateUrl('backend_user_list'));

    }

    public function deleteAction($id)
    {
        $gift = $this->getDoctrine()->getManager()->getRepository('FlorProjectBackendBundle:Gift')->find($id);
        if ($gift != null)
        {
            return $this->render('FlorProjectBackendBundle:Gift:delete.html.twig', array(
                    'gift' => $gift
                ));
        }

        $this->get('session')->getFlashBag()->add('error', "No se encontrado el regalo solicitado");
        return $this->redirect($this->generateUrl('backend_gift_list'));

    }

    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $gift = $em->getRepository('FlorProjectBackendBundle:Gift')->find($request->get('gift_id'));
        if ($gift != null)
        {
            $form = $this->createForm(new GiftFormType(), $gift);
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $em->merge($gift);
                $em->flush();
                $this->get('session')->getFlashBag()->add('message', "Regalo actualizado con éxito");
                return $this->redirect($this->generateUrl('backend_gift_list'));
            }
            $this->get('session')->getFlashBag()->add('error', "Debe llenar correctamente los campos");
        }
        else
            $this->get('session')->getFlashBag()->add('error', "No se encontrado el regalo solicitado");

        return $this->render('FlorProjectBackendBundle:Gift:edit.html.twig', array(
                'form' => $form->createView(),
                'gift' => $gift
            ));
    }

    public function deleteConfirmedAction(Request $request)
    {
        $id = $request->get('gift_id');
        $em = $this->getDoctrine()->getManager();
        $gift = $this->getDoctrine()->getManager()->getRepository('FlorProjectBackendBundle:Gift')->find($id);
        if ($gift != null) {
            try
            {
                $em->remove($gift);
                $em->flush();
                $this->get('session')->getFlashBag()->add('message', "Regalo eliminado con éxito");
            }
            catch (\Exception $ex)
            {
                $this->get('session')->getFlashBag()->add('message', "No se puede eliminar un regalo referenciado");
            }
        }
        else
            $this->get('session')->getFlashBag()->add('error', "No se encontrado el usuario solicitado");

        return $this->redirect($this->generateUrl('backend_gift_list'));
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
        $filterPriceFrom = doubleval($request->get('filter_price_from'));
        $filterPriceTo = doubleval($request->get('filter_price_to')) > 0 ? $request->get('filter_price_to') : 9999.99;
        $filterFamilyName = '%'.$request->get('filter_family_name') . "%";
        $filterGiftType = '%'.$request->get('filter_gift_type') . "%";


        $em = $this->getDoctrine()->getManager();

        $totalRecord = $em->createQuery(
            'SELECT count(g.id) FROM FlorProjectBackendBundle:Gift g JOIN g.family f WHERE g.name LIKE :filterName
             AND g.price >= :filterPriceFrom AND g.price <= :filterPriceTo AND f.familyName LIKE :filterFamilyName
             AND f.giftType LIKE :filterGiftType ORDER BY g.id DESC '
        )->setParameter('filterName', $filterName)
            ->setParameter('filterPriceFrom', $filterPriceFrom)
            ->setParameter('filterPriceTo', $filterPriceTo)
            ->setParameter('filterFamilyName', $filterFamilyName)
            ->setParameter('filterGiftType', $filterGiftType)
            ->getSingleScalarResult();

        $queryObject = $em->createQuery(
            'SELECT g FROM FlorProjectBackendBundle:Gift g JOIN g.family f WHERE g.name LIKE :filterName
             AND g.price >= :filterPriceFrom AND g.price <= :filterPriceTo AND f.familyName LIKE :filterFamilyName
             AND f.giftType LIKE :filterGiftType ORDER BY g.id DESC '
        )->setParameter('filterName', $filterName)
            ->setParameter('filterPriceFrom', $filterPriceFrom)
            ->setParameter('filterPriceTo', $filterPriceTo)
            ->setParameter('filterFamilyName', $filterFamilyName)
            ->setParameter('filterGiftType', $filterGiftType)
         ->setFirstResult($pageNumber * $pageLength)
         ->setMaxResults($pageLength);



        $gifts = $queryObject->getResult();
        $data = array();
        foreach($gifts as $actualGift)
        {
            $controls = '<a href="/admin/gift/edit/'. $actualGift->getId() .'" class="btn btn-xs default"><i class="fa fa-search"></i></a>' . '<a href="/admin/gift/delete/'.$actualGift->getId().'" class="btn btn-xs default"><i class="fa fa-times"></i></a>';
            $image = $this->renderView('FlorProjectBackendBundle:Gift:thumbs.html.twig', array(
                'gift' => $actualGift
            ));


            $data[] = array(
                $actualGift->getId(),
                $image,
                $this->getTypeName($actualGift->getFamily()->getGiftType()),
                $actualGift->getName(),
                number_format($actualGift->getPrice(),2) . " EUR",
                $actualGift->getFamily()->getFamilyName(),
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
