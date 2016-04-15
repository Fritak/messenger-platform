<?php

namespace fritak\MessengerPlatform;

use fritak\MessengerPlatform\MessengerPlatformException;

/**
 * Base object.
 *
 * 
 * @package fritak\MessengerPlatform
 */
abstract class BaseObject
{

    /** @var array */
    protected $data = [];
    
    /**
     * Basic config.
     *
     * @param $array Config.
     */
    public function __construct($array)
    {
        foreach($array AS $key => $val)
        {
            $this->data[$key] = is_array($val)? new Config($val) : $val;
        }
    }
    
    public function &__get($key)
    {
        if(empty($this->data[$key]))
        {
            throw new MessengerPlatformException('Key "' . $key . '" is missing in "' . (get_class($this)) . '".', 1);
        }

        return $this->data[$key];
    }
    
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }
    
    public function getDataForCall()
    {
        $result = [];
        foreach($this->data as $key => $item)
        {
            $result[$key] = $item;
        }
        
        return $result;
    }
}