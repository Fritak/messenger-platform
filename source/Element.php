<?php

namespace fritak\MessengerPlatform;


/**
 * Item in order.
 * 
 * @package fritak\MessengerPlatform
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference#request
 */
class Element
{
    /** @var string Bubble title */
    protected $title = NULL;
    
    /** @var string URL that is opened when bubble is tapped. */
    protected $itemUrl = NULL;
    
    /** @var string Bubble image */
    protected $imageUrl = NULL;

    /** @var string Bubble subtitle */
    protected $subtitle = NULL;

    /** @var array Buttons. */
    protected $buttons = [];


    public function __construct($title, $subtitle = '', $itemUrl = '', $imageUrl = '', $buttons = [])
    {
        $this->title    = $title;
        $this->itemUrl  = $itemUrl;
        $this->imageUrl = $imageUrl;
        $this->subtitle = $subtitle;
        $this->buttons  = $buttons;
    }


    public function getDataForCall()
    {
        $request = ['title' => $this->title,];
        
        if (!empty($this->itemUrl)) 
        {
            $request['item_url'] = $this->itemUrl;
        }
        
        if (!empty($this->imageUrl)) 
        {
            $request['image_url'] = $this->imageUrl;
        }
        
        if (!empty($this->subtitle)) 
        {
            $request['subtitle'] = $this->subtitle;
        }
        
        if (!empty($this->buttons)) 
        {
            $request['buttons'] = [];

            foreach ($this->buttons as $button) 
            {
                $request['buttons'][] = $button->getDataForCall();
            }
        }

        return $request;
    }
}

/**
 * 
 * @package fritak\MessengerPlatform
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference#request
 */
class Button
{
    const TYPE_WEB = "web_url";
    const TYPE_POSTBACK = "postback";

    /** @var string Value is web_url or postback, use constants. */
    protected $type = NULL;

    /** @var string Button title */
    protected $title = NULL;

    /** @var string For web_url buttons, this URL is opened in a mobile browser when the button is tapped. For postback buttons, this data will be sent back to you via webhook. */
    protected $url = NULL;

    
    public function __construct($title, $type, $url)
    {
        $this->type = $type;
        $this->title = $title;
        $this->url = $url;
    }

    public function getDataForCall()
    {
        $result = 
        [ 
            'type' => $this->type,
            'title' => $this->title,
        ];

        switch($this->type)
        {
            case self::TYPE_POSTBACK:
                $result['payload'] = $this->url;
                break;
            case self::TYPE_WEB:
                $result['url'] = $this->url;
                break;
        }

        return $result;
    }
}
