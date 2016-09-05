<?php

namespace VkSdk\Photos\Messages\Includes;

use VkSdk\Includes\Request;

class GetMessagesUploadServer extends Request
{

    private $upload_url;
    private $album_id;
    private $user_id;

    public function getUploadUrl()
    {
        return $this->upload_url;
    }

    public function getAlbumId()
    {
        return $this->album_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function doRequest()
    {
        $this->setMethod("photos.getMessagesUploadServer");

        $json = $this->execApi();
        if (!$json) {
            return false;
        }

        if (isset($json->response) && $json->response &&
            isset($json->response->upload_url) && $json->response->upload_url
        ) {
            $this->upload_url = $json->response->upload_url;

            return true;
        }
        return false;
    }

}