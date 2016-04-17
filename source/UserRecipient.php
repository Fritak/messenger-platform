<?php

namespace fritak\MessengerPlatform;

/**
 * Object of recipient of message.
 *
 * 
 * @package fritak\MessengerPlatform
 */
class UserRecipient
{
    public $fib;
    public $phone;
    
    /**
     * 
     * @param int $fib an id that was retrieved through the Messenger entry points or through the Messenger callbacks
     * @param int $phone With the pages_messaging_phone_number permission, you can send messages to people in Messenger 
     * if you have their phone number and their consent to be contacted by you. We will match the person in Messenger for verified phone numbers.
     * @see https://developers.facebook.com/docs/messenger-platform/implementation
     */
    public function __construct($fib = NULL, $phone = NULL)
    {
        if(empty($fib) && empty($phone))
        {
            
        }
        
        $this->fib   = $fib;
        $this->phone = $phone;
    }
    
    public function getDataForCall()
    {
        $result = [];
        
        if(!empty($this->fib))
        {
            $result['id'] = $this->fib;
        }
        
        if(!empty($this->phone))
        {
            $result['phone_number'] = $this->phone;
        }
        
        return $result;
    }
}