<?php

namespace fritak\MessengerPlatform;

use fritak\MessengerPlatform\Config;
use fritak\MessengerPlatform\Response;

/**
 * Gate for communication with api.
 *
 * @package fritak\MessengerPlatform
 */
class Gate
{
    /** @var fritak\MessengerPlatform\Config Config for application */
    public $config;
    
    /** @var string GET type of request. */
    const TYPE_GET = "GET";
    
    /** @var string POST type of request. */
    const TYPE_POST = "POST";
    
    const URL_SUBSCRIBED_APPS = 'subscribed_apps';
    const URL_MESSAGES        = 'messages';

    public function __construct(Config &$config)
    {
        $this->config = &$config;
    }
    
    /**
     * Request to API
     *
     * @param string $requestUrl Url for request.
     * @param array $data Data for send.
     * @param string $type Type of request GET/POST
     * @return fritak\MessengerPlatform\Response
     */
    public function request($requestUrl, $data = [], $type = self::TYPE_POST)
    {
        $callUrl = $this->config->facebookApiUrl . $requestUrl;

        if($type === self::TYPE_GET) 
        {
            $callUrl .= '?' . $this->prepareData($data);
        }

        $curl = curl_init($callUrl);
        
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt($curl, CURLOPT_HEADER, 0);
        
        if($type === self::TYPE_POST) 
        {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->prepareData($data));
        }

        $response = new Response(curl_exec($curl));
        
        $curlError = curl_error($curl);
        if(!empty($curlError))
        {
            throw new MessengerPlatformException('Gate exception (curl error) #3: ' . $curlError);
        }
        
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if($statusCode != 200)
        {
            throw new MessengerPlatformException('Gate exception #2: ' . $response->error->message, 2);
        }
        
        curl_close($curl);

        return $response;
    }
    
    /**
     * Prepare data for request.
     * 
     * @param array $data
     * @return string
     */
    protected function prepareData($data)
    {
        if(!is_array($data))
        {
            $data = [];
        }
        
        $data['access_token'] = $this->config->accessToken;
        
        return http_build_query($data);
    }
    
    protected function getHeaders()
    {
        return ['Content-Type: application/json',];
    }

}