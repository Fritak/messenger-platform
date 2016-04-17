<?php

namespace fritak\MessengerPlatform;

/**
 * Class used for structured messages.
 *
 * @package fritak\MessengerPlatform
 */
class StructuredMessage extends MessageSend
{
    const ATTACHMENT_TYPE_IMAGE = 'image';
    const ATTACHMENT_TYPE_TEMPLATE = 'template';
    
    const TEMPLATE_PAYLOAD_TYPE_BUTTON  = "button";
    const TEMPLATE_PAYLOAD_TYPE_GENERIC = "generic";
    const TEMPLATE_PAYLOAD_TYPE_RECEIPT = "receipt";

    protected $typeAttachment = NULL;
    protected $typePayolad    = NULL;
    

    public function __construct($recipientId, 
                                $data, 
                                $notificationType = self::NOTIFICATION_TYPE_REGULAR, 
                                $typeAttachment = self::ATTACHMENT_TYPE_TEMPLATE, 
                                $typePayolad = self::TEMPLATE_PAYLOAD_TYPE_GENERIC)
    {
        $this->recipientId     = $recipientId;
        $this->data             = $data;
        $this->notificationType = $notificationType;
        $this->typeAttachment   = $typeAttachment;
        $this->typePayolad      = $typePayolad;
    }

    public function getDataForCall()
    {
        $payload = [];
        
        if($this->typeAttachment == self::ATTACHMENT_TYPE_IMAGE)
        {
            if(isset($this->data['url']))
            {
                $payload['url'] = $this->data['url'];
            }
        }
        else
        {
            $payload['template_type'] = $this->typePayolad;
        }

        switch ($this->typePayolad)
        {
            case self::TEMPLATE_PAYLOAD_TYPE_BUTTON:
                $payload['text'] = $this->data['text'];
                $buttons = [];

                foreach ($this->data['buttons'] as $button) 
                {
                    $buttons[] = $button->getDataForCall();
                }
                $payload['buttons'] = $buttons;
                break;

            case self::TEMPLATE_PAYLOAD_TYPE_GENERIC && $this->typeAttachment != self::ATTACHMENT_TYPE_IMAGE:
                $elements = [];

                foreach ($this->data as $element)
                {
                    $elements[] = $element->getDataForCall();
                }
                
                $payload['elements'] = $elements;
                break;

            case self::TEMPLATE_PAYLOAD_TYPE_RECEIPT:
                $payload = array_replace($payload, $this->data->getDataForCall());
            break;
        }
        
        $this->messageTemplate = 
        [
            'attachment' => 
            [
                'type' => $this->typeAttachment,
                'payload' => $payload,
            ]
        ];

        return parent::getDataForCall();
    }
}