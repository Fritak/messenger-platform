<?php

namespace fritak\MessengerPlatform;

use fritak\MessengerPlatform\StructuredMessage;

/**
 * Class used for Welcome Message.
 *
 * @package fritak\MessengerPlatform
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference#welcome_message_configuration
 */
class WelcomeMessage extends MessageSend
{
    public $callToActions = [];
    
    public function __construct($text, $template = null)
    {
        $this->addNext($text, $template);
    }
    
    /**
     * You can set the welcome message to be text or Structured Message.
     * 
     * @param string $text
     * @param StructuredMessage $template Structured Message
     */
    public function addNext($text, $template = null)
    {
        if(!empty($text))
        {
            $this->callToActions = ['message' => ['text' => $text]];
        }
        
        if(!empty($template))
        {
            if($template instanceof StructuredMessage)
            {
                $this->callToActions = ['message' => [$template->getDataForCall(FALSE)]];
            }
            else
            {
                throw new MessengerPlatformException('Template for welcome message is not StructuredMessage.', 4);
            }
        }
    }

    public function getDataForCall()
    {

        return [
            'setting_type'      => 'call_to_actions',
            'thread_state'      => 'new_thread',
            'call_to_actions'   => $this->callToActions
        ];
    }
}