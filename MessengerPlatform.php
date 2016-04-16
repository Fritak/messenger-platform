<?php

namespace fritak;

use fritak\MessengerPlatform\Gate;
use fritak\MessengerPlatform\Config;
use fritak\MessengerPlatform\Receipt;
use fritak\MessengerPlatform\MessageSend;
use fritak\MessengerPlatform\StructuredMessage;

/**
 * Class for Messenger Platform API. 
 *
 * @package fritak\MessengerPlatform
 * @see https://developers.facebook.com/docs/messenger-platform/implementation
 */
class MessengerPlatform
{
    /** @var fritak\MessengerPlatform\Config Config for application */
    public $config;
    
    /** @var fritak\MessengerPlatform\ParseRequest Parse request. */
    public $request;

    /** @var fritak\MessengerPlatform\Gate Gate for API. */
    protected $gate = NULL;


    public function __construct($config, $request = [])
    { 
        $this->loadConfig($config);
        $this->gate = new Gate($this->config);
        $this->request = new \fritak\MessengerPlatform\ParseRequest($request);
    }

    /**
     * You can load another config  later on.
     * 
     * @param array $config
     */
    public function loadConfig($config)
    {
        $this->config = new Config($config);
    }
    
    /**
     * In order for your webhook to receive events for a specific page, you must subscribe your app to the page. 
     * 
     * @return fritak\MessengerPlatform\Response Response.
     * @see https://developers.facebook.com/docs/messenger-platform/implementation#subscribe_app_pages
     */
    public function subscribe()
    {
        return $this->gate->request(Gate::URL_SUBSCRIBED_APPS);
    }
    
    /**
     * Send a simple text message.
     * 
     * @param int $recipientId This must be an id that was retrieved through the Messenger entry points or through the Messenger callbacks.
     * @param string $textMessage Text.
     * @param string $notificationType One of 3 types of notification, use constants (eg. NOTIFICATION_TYPE_REGULAR)
     * @return fritak\MessengerPlatform\Response
     * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference#guidelines
     */
    public function sendMessage($recipientId, $textMessage, $notificationType = MessageSend::NOTIFICATION_TYPE_REGULAR)
    {
        $message = new MessageSend($recipientId, $textMessage, $notificationType);
        
        return $this->gate->request(Gate::URL_MESSAGES, $message->getDataForCall());
    }
    
    /**
     * Send an image (file).
     * 
     * @param int $recipientId This must be an id that was retrieved through the Messenger entry points or through the Messenger callbacks.
     * @param string $url Image URL.
     * @param string $notificationType One of 3 types of notification, use constants (eg. NOTIFICATION_TYPE_REGULAR)
     * @return fritak\MessengerPlatform\Response
     * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference#guidelines
     */
    public function sendImage($recipientId, $url, $notificationType = MessageSend::NOTIFICATION_TYPE_REGULAR)
    {
        $structuredMessage = new StructuredMessage($recipientId, ['url' => $url], $notificationType, StructuredMessage::ATTACHMENT_TYPE_IMAGE);
        
        return $this->gate->request(Gate::URL_MESSAGES, $structuredMessage->getDataForCall());
    }
    
    /**
     * Send a structured Message - button template.
     * 
     * @param int $recipientId This must be an id that was retrieved through the Messenger entry points or through the Messenger callbacks.
     * @param string $text Text.
     * @param array $buttons Array of fritak\MessengerPlatform\Button.
     * @param string $notificationType One of 3 types of notification, use constants (eg. NOTIFICATION_TYPE_REGULAR)
     * @return fritak\MessengerPlatform\Response
     * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference#guidelines
     */
    public function sendButton($recipientId, $text, $buttons, $notificationType = MessageSend::NOTIFICATION_TYPE_REGULAR)
    {
        $structuredMessage = new StructuredMessage($recipientId, 
                                                   ['text' => $text, 'buttons' => $buttons], 
                                                   $notificationType, 
                                                   StructuredMessage::ATTACHMENT_TYPE_TEMPLATE,
                                                   StructuredMessage::TEMPLATE_PAYLOAD_TYPE_BUTTON);
        
        return $this->gate->request(Gate::URL_MESSAGES, $structuredMessage->getDataForCall());
    }
    
    /**
     * Send a structured Message - receipt template.
     * 
     * @param int $recipientId This must be an id that was retrieved through the Messenger entry points or through the Messenger callbacks.
     * @param Receipt $receipt
     * @param string $notificationType One of 3 types of notification, use constants (eg. NOTIFICATION_TYPE_REGULAR)
     * @return fritak\MessengerPlatform\Response
     * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference#guidelines
     */
    public function sendReceipt($recipientId, Receipt $receipt, $notificationType = MessageSend::NOTIFICATION_TYPE_REGULAR)
    {
        $structuredMessage = new StructuredMessage($recipientId, 
                                                   $receipt, 
                                                   $notificationType, 
                                                   StructuredMessage::ATTACHMENT_TYPE_TEMPLATE,
                                                   StructuredMessage::TEMPLATE_PAYLOAD_TYPE_RECEIPT);
        
        return $this->gate->request(Gate::URL_MESSAGES, $structuredMessage->getDataForCall());
    }
    
    /**
     * Send a complex message.
     * 
     * @param fritak\MessengerPlatform\MessageSend|fritak\MessengerPlatform\StructuredMessage $message
     * @return fritak\MessengerPlatform\Response
     */
    public function sendComplexMeesage($message)
    {
        return $this->gate->request(Gate::URL_MESSAGES, $message->getDataForCall());
    }
    
    /**
     * Get messages received. Returns FALSE if request don`t have messages.
     * 
     * @return boolean|array Array of \fritak\MessengerPlatform\MessageReceived.
     */
    public function getMessagesReceived()
    {
        return $this->request->getMessagesReceived();
    }
    
    /**
     * Check if request is subscribe.
     * 
     * @return boolean Is request subscribe?
     */
    public function checkSubscribe()
    {
        return $this->request->checkSubscribe($this->config->webhookToken);
    }
    
}