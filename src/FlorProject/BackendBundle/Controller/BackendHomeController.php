<?php

namespace FlorProject\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BackendHomeController extends Controller
{
    public function dashboardAction()
    {
        return $this->render('FlorProjectBackendBundle:Home:dashboard.html.twig');
    }
}
