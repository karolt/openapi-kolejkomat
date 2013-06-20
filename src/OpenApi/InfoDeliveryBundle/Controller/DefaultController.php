<?php

namespace OpenApi\InfoDeliveryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {

        return $this->render('InfoDeliveryBundle:Default:index.html.twig');
    }
}
