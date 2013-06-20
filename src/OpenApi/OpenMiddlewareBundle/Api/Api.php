<?php
namespace OpenApi\OpenMiddlewareBundle\Api;

use Guzzle\Http\Client;

class Api
{
    /**
     * @var \Guzzle\Http\Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    public function __construct(Client $httpClient, $user, $password)
    {
        $this->httpClient = $httpClient;
        //API uzywa SSLv3 :|
        $this->httpClient->setDefaultOption(Client::CURL_OPTIONS, [CURLOPT_SSLVERSION => 3]);

        $this->user = $user;
        $this->password = $password;

    }

    protected function makeCall($method, $data)
    {
        $url = $method . "?" . http_build_query($data);

        //veryfy => false pozwala pominac weryfikacje nieogarnietego certyfikatu
        $request = $this->httpClient->get($url, null, ['verify' => false])->setAuth($this->user, $this->password);
        $response = $request->send();

        return $response;
    }


}
