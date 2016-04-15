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
    
    public function __construct($data)
    {
        $this->recipient = new Recipient(['id' => $data->recipient->id]);
        $this->sender    = new Sender(['id' => $data->sender->id]);
        $this->timestamp = $data->timestamp;

        $this->message = new Message([
            'mid'  => $data->message->mid,
            'seq'  => $data->message->seq,
            'text' => $data->message->text,
        ]);
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