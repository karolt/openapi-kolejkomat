<?php
namespace OpenApi\LayoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    public function helloAction()
    {
        return $this->render("LayoutBundle::test.html.twig");
    }
}
