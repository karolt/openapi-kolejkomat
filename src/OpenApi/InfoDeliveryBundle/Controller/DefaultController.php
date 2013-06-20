<?php

namespace OpenApi\InfoDeliveryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $sms = new \OpenApi\OpenMiddlewareBundle\Sms('+48513050541', '+48513050595', "wiadomosc_testowa");
        /** @var $sendSmsApi \OpenApi\OpenMiddlewareBundle\Api\SendSms */
        $sendSmsApi = $this->get('open_middleware.send_sms');
        $sendSmsApi->send($sms);

        return $this->render('InfoDeliveryBundle:Default:index.html.twig');
    }
}
