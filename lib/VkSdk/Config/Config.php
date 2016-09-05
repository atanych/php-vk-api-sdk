<?php

namespace VkSdk\Config;


use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class Config
{


    public static function getParam($param, $required = false)
    {
        throw  new \Exception('use Request constructor');
    }
    
}