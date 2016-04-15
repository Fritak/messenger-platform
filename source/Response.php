<?php

namespace fritak\MessengerPlatform;


/**
 * Handling response.
 * 
 * @property-read boolean $success Success?
 * @property-read int $recipient_id  (sending message) Recipent ID.
 * @property-read string $message_id (sending message) Message ID.
 * 
 * @package fritak\MessengerPlatform
 */
class Response extends BaseObject
{

    /**
     * 
     *
     * @param $response Response from api.
     */
    public function __construct($response)
    { 
        $decoded = is_array($response)? $response : json_decode($response);

        if(empty($decoded))
        {
            return NULL;
        }
        
        foreach($decoded AS $key => $val)
        {
            $this->data[$key] = is_array($val)? new Response($val) : $val;
        }
    }

}