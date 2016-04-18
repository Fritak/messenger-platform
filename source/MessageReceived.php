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
        
    /** @var fritak\MessengerPlatform\Delivery Message. */
    public $delivery;
    
    public function __construct($data)
    {
        $this->recipient = new Recipient(['id' => $data->recipient->id]);
        $this->sender    = new Sender(['id' => $data->sender->id]);
        $this->timestamp = isset($data->timestamp)? $data->timestamp : null;

        if(isset($data->message))
        {
            $this->message = new Message([
                'mid'  => $data->message->mid,
                'seq'  => $data->message->seq,
                'text' => $data->message->text,
            ]);
        }
        
        if(isset($data->delivery))
        {
            $this->delivery = new Delivery($data->delivery);
        }
        
        if(isset($data->postback))
        {
            $this->postback = new Delivery($data->postback);
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
 * @property-read int $mid Message ID.
 * @property-read int $seq Message sequence number. 
 * @property-read string $text Text of message. 
 * @package fritak\MessengerPlatform
 */
class Message extends BaseObject {}