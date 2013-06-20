<?php
namespace OpenApi\OpenMiddlewareBundle\Api;

use OpenApi\OpenMiddlewareBundle\Sms;

class SendSms extends Api
{
    public function send(Sms $sms)
    {
        $data = [
            'msg'   => $sms->getMessage(),
            'from'  => $sms->getFrom(),
            'to'    => $sms->getTo()
        ];

        $response = $this->makeCall("sendSms", $data);

       //var_dump($response->getBody(true));
    }
}
