<?php

namespace fritak\MessengerPlatform;


/**
 * Object containing event data.
 *
 * @package fritak\MessengerPlatform
 * @see https://developers.facebook.com/docs/messenger-platform/webhook-reference#common_format
 */
class MessageReceived
{
    /** @var int Page ID of page */
    public $id;
    
    /** @var int Time of update */
    public $time;
    
    /** @var array array of objects containing data related to messaging */
    public $messaging = [];
    
    public function __construct($data)
    {
        $this->id         = $data->id;
        $this->time       = $data->time;
        
        foreach($data->messaging AS $messaging)
        {
            $this->messaging[] = new Messaging($messaging);
        }

    }
}

/**
 * Object containing data related to messaging.
 *
 * @package fritak\MessengerPlatform
 * @see https://developers.facebook.com/docs/messenger-platform/webhook-reference#received_message
 */
class Messaging
{
    /** @var fritak\MessengerPlatform\Recipient Recipient. */
    public $recipient;
    
    /** @var fritak\MessengerPlatform\Sender Sender. */
    public $sender;
    
    /** @var int Timestamp of message. */
    public $timestamp;
    
    /** @var fritak\MessengerPlatform\Message Message. */
    public $message;
    
    /** @var fritak\MessengerPlatform\Delivery Message. */
    public $delivery;
        
    /** @var fritak\MessengerPlatform\Optin Message. */
    public $optin;
    
    public function __construct($data)
    {
        $this->recipient = new Recipient(['id' => $data->recipient->id]);
        $this->sender    = new Sender(['id' => $data->sender->id]);
        $this->timestamp = isset($data->timestamp)? $data->timestamp : null;

        if(isset($data->message))
        { 
            $this->message = new Message($data->message);
        }
        
        if(isset($data->delivery))
        {
            $this->delivery = new Delivery($data->delivery);
        }
        
        if(isset($data->postback))
        {
            $this->postback = new Postback($data->postback);
        }
        
        if(isset($data->optin))
        {
            $this->optin = new Optin($data->optin);
        }
    }
}

/**
 * This indicates there are updates that are of delivery type
 * @package fritak\MessengerPlatform
 * @see https://developers.facebook.com/docs/messenger-platform/webhook-reference#message_delivery
 */
class Delivery 
{
    /** @var object Array containing message IDs of messages that were delivered. Field may not be present. */
    public $mids = null;
    
    /** @var int All messages that were sent before this timestamp were delivered. */
    public $watermark = null;
    
    /** @var int Sequence number */
    public $seq = null;
    
    public function __construct($delivery)
    {
        $this->mids      = $delivery->mids;
        $this->watermark = $delivery->watermark;
        $this->seq       = $delivery->seq;
    }
}

/**
 * @package fritak\MessengerPlatform
 * @see https://developers.facebook.com/docs/messenger-platform/webhook-reference#postback
 */
class Postback 
{
    /** @var mixed payload parameter that was defined with the button */
    public $payload = null;
    
    public function __construct($postback)
    {
        $this->payload = $postback->payload;
    }
}

/**
 * Authentication Callback
 * @see https://developers.facebook.com/docs/messenger-platform/webhook-reference#auth
 */
class Optin
{
    /** @var mixed data-ref parameter that was defined with the entry point */
    public $ref = null;
    
    public function __construct($optin)
    {
        $this->ref = $optin->ref;
    }
}

/**
 * 
 */
class Attachment
{
    const TYPE_LOCATION = 'location';

    /** @var string Type of attachment - Enum(image, video, audio). */
    public $type = null;
    
    /** @var string URL of attachment */
    public $url = null;

    public $payload;

    public function __construct($attachment)
    {
        $this->type = $attachment->type;

        if ($attachment->payload) {
            if (isset($attachment->payload->coordinates)) {
                $this->payload = new Payload($attachment->payload->coordinates);
            } else {
                $this->payload = new Payload($attachment->payload);
            }
        }
    }
}

class Payload extends BaseObject {}

/**
 * @property-read int $id Recipient user id.
 * @package fritak\MessengerPlatform
 */
class Recipient extends BaseObject {}

/**
 * @property-read int $id Sender user id.
 * @package fritak\MessengerPlatform
 */
class Sender extends BaseObject {}

/**
 * You may receive text messsages or messages with attachments (image, video, audio).
 * @package fritak\MessengerPlatform
 */
class Message 
{
    /** @var object Message ID. */
    public $mid = null;
    
    /** @var int Message sequence number.  */
    public $seq = null;
    
    /** @var string Text of message.  */
    public $text = null;
    
    /** @var string Array containing attachment data  */
    public $attachments = null;

    public $quick_reply;

    public function __construct($message)
    {
        if(isset($message->mid))
        {
            $this->mid = $message->mid;
        }
        
        if(isset($message->seq))
        {
            $this->mid = $message->seq;
        }
        
        if(isset($message->text))
        {
            $this->text = $message->text;
        }
        
        if(isset($message->attachments))
        {
            $this->attachments = [];
            
            foreach($message->attachments AS $attachment)
            {
                $this->attachments[] = new Attachment($attachment);
            }
        }

        if (isset($message->quick_reply)) {
            $this->quick_reply = new Payload($message->quick_reply);
        }
    }
}