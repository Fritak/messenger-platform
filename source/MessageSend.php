<?php

namespace fritak\MessengerPlatform;


/**
 * Class for sending text message.
 *
 * @package fritak\MessengerPlatform
 */
class MessageSend extends BaseObject
{
    const NOTIFICATION_TYPE_REGULAR     = 'REGULAR';
    const NOTIFICATION_TYPE_NO_PUSH     = 'NO_PUSH';
    const NOTIFICATION_TYPE_SILENT_PUSH = 'SILENT_PUSH';
    
    public $recipientId;
    public $textMessage;
    public $messageTemplate;
    public $notificationType;
    
    public function __construct($recipientId, $textMessage, $notificationType = self::NOTIFICATION_TYPE_REGULAR)
    {
        $this->recipientId      = $recipientId;
        $this->textMessage      = $textMessage;
        $this->notificationType = $notificationType;
    }

    public function getDataForCall()
    {
        if(empty($this->messageTemplate))
        {
            $this->messageTemplate = ['text' => $this->textMessage];
        }

        return [
            'message'           => $this->messageTemplate,
            'recipient'         => ['id' => $this->recipientId],
            'notification_type' => $this->notificationType
        ];
    }
}