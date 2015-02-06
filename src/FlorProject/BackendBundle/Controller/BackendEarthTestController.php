<?php
/**
 * Created by PhpStorm.
 * User: gandalf
 * Date: 20/11/2014
 * Time: 04:41 PM
 */

namespace FlorProject\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class BackendEarthTestController extends Controller
{
    public function testMapAction()
    {
        return $this->render('FlorProjectBackendBundle:Home:maptest.html.twig');
    }

    public function kmlAction()
    {
        $kmlResponse = $this->render('FlorProjectBackendBundle:Home:kmltest.kml.twig');
        $response = new Response($kmlResponse);
        $response->headers->set('Content-Type', 'application/vnd.google-earth.kml+xml');
        return $response;
    }
} 