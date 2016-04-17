<?php

namespace fritak\MessengerPlatform;

use fritak\MessengerPlatform\MessengerPlatformException;


/**
 * Parse request from webhook.
 *
 * @package fritak\MessengerPlatform
 */
class ParseRequest
{
    const MODE_SUBSCRIBE = 'subscribe';
    
    /** @var fritak\MessengerPlatform\Response */
    public $response;
    
    /** @var string */
    protected $mode;
    
    /** @var string */
    protected $verifyWebhookToken;
    
    /** @var string */
    protected $challenge;


    /**
     * Parse request from webhook.
     *
     * @param $data Data from request.
     */
    public function __construct($data)
    {
        $this->mode                = isset($data['hub_mode'])?         $data['hub_mode']           : NULL;
        $this->challenge           = isset($data['hub_challenge'])?    $data['hub_challenge']      : NULL;
        $this->verifyWebhookToken  = isset($data['hub_verify_token'])? $data['hub_verify_token']   : NULL;
        
        $this->response = is_string($data)? json_decode($data) : $data;

    }

    /**
     * Verify request token is valid.
     * 
     * @param string $webhookToken WebhookToken from our configuration.
     * @return boolean Is token from request valid?
     */
    public function verifyWebhookToken($webhookToken)
    {
        return $this->verifyWebhookToken == $webhookToken;
    }
    
    public function isSubscribe()
    {
        return !empty($this->mode) && $this->mode == self::MODE_SUBSCRIBE;
    }
    
    public function isMessage()
    {
        try
        { 
            if(!isset($this->response->entry['0']->messaging)) 
            {
                return FALSE;
            }
            
            $messaging = $this->response->entry['0']->messaging;
        }
        catch(MessengerPlatformException $e)
        {
            return FALSE;
        }

        return !empty($messaging);
    }
    
    /**
     * Check if request is subscribe.
     * 
     * @param string $webhookToken WebhookToken from our configuration.
     * @return boolean Is request subscribe?
     */
    public function checkSubscribe($webhookToken)
    {
        return $this->verifyWebhookToken($webhookToken) && $this->isSubscribe();
    }
    
    public function getChallenge()
    {
        return $this->challenge;
    }
    
    /**
     * Get messages received. Returns FALSE if request don`t have messages.
     * 
     * @return boolean|array Array of \fritak\MessengerPlatform\MessageReceived.
     */
    public function getMessagesReceived()
    {
        if($this->isMessage())
        {
            $messages = [];
            foreach($this->response->entry AS $entry)
            {
                $messages[] = new MessageReceived($entry);
            }
            
            return $messages;
        }
        
        return FALSE;
    }
}