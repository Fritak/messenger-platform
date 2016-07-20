<?php

namespace fritak\MessengerPlatform;

class QuickReply extends BaseObject
{
    public $title;
    public $payload;

    public function __construct($title, $payload)
    {
        $this->title = $title;
        $this->payload = $payload;
    }

    public function getDataForCall()
    {
        return [
            'content_type'  => 'text',
            'title'         => $this->title,
            'payload'       => $this->payload,
        ];
    }
}


class MessageSendWithReplies extends MessageSend
{
    public $replies = [];

    public function __construct($recipient, $textMessage, $data, $notificationType = self::NOTIFICATION_TYPE_REGULAR)
    {
        parent::__construct($recipient, $textMessage, $notificationType);
        $this->replies = $data;
    }

    public function getDataForCall()
    {
        if(empty($this->messageTemplate))
        {
            $this->messageTemplate = ['text' => $this->textMessage];

            if ($this->replies) {
                $this->messageTemplate['quick_replies'] = [];
                foreach ($this->replies as $reply) {
                    $this->messageTemplate['quick_replies'][] = $reply->getDataForCall();
                }
            }
        }

        return [
            'message'           => $this->messageTemplate,
            'recipient'         => $this->recipient->getDataForCall(),
            'notification_type' => $this->notificationType
        ];
    }
}
