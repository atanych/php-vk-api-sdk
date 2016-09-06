<?php

namespace VkSdk\Messages;

use VkSdk\Includes\Request;
use VkSdk\Messages\Includes\MessageItem;

class MessagesGetLongPollServer extends Request
{

    private $key;
    private $server;
    private $ts;

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param mixed $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     * @return mixed
     */
    public function getTs()
    {
        return $this->ts;
    }

    /**
     * @param mixed $ts
     */
    public function setTs($ts)
    {
        $this->ts = $ts;
    }

    public function doRequest()
    {
        $this->setMethod("messages.getLongPollServer");

        $json = $this->execApi();
        if (!$json) {
            return false;
        }

        if (!is_object($json) && $json < 0) {
            return $json;
        }
        if (
            isset($json->response) && $json->response &&
            isset($json->response->server) &&
            isset($json->response->ts) &&
            isset($json->response->key)
        ) {

            $this->setServer($json->response->server);
            $this->setKey($json->response->key);
            $this->setTs($json->response->ts);

            return true;
        }

        return false;
    }

}