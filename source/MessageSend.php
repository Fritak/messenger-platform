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
    
    public $recipient;
    public $textMessage;
    public $messageTemplate;
    public $notificationType;
    
    public function __construct($recipient, $textMessage, $notificationType = self::NOTIFICATION_TYPE_REGULAR)
    {
        $this->recipient        = $this->getRecipient($recipient);
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
            'recipient'         => $this->recipient->getDataForCall(),
            'notification_type' => $this->notificationType
        ];
    }
    
    public function getRecipient($recipient)
    {
        return is_numeric($recipient)? new UserRecipient($recipient) : $recipient;
    }
}