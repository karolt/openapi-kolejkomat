<?php

namespace OpenApi\OpenMiddlewareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OpenMiddlewareBundle:Default:index.html.twig', array('name' => $name));
    }
}
