<?php

namespace VkSdk\Photos\Wall\Includes;

use VkSdk\Photos\Includes\Server;

class UploadWallPhoto extends GetWallUploadServer implements Server
{

    /**
     * @var GetWallUploadServer
     */
    private $upload_server;
    private $photo_url;
    private $server;
    private $photo;
    private $hash;

    /**
     * @return GetWallUploadServer
     */
    public function getUploadServer()
    {
        return $this->upload_server;
    }

    /**
     * @param GetWallUploadServer $upload_server
     * @return UploadWallPhoto
     */
    public function setUploadServer(GetWallUploadServer $upload_server)
    {
        $this->upload_server = $upload_server;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhotoUrl()
    {
        return $this->photo_url;
    }

    /**
     * @param mixed $photo_url
     * @return UploadWallPhoto
     */
    public function setPhotoUrl($photo_url)
    {
        $this->photo_url = $photo_url;
        return $this;
    }
    
    public function checkUploadServer(){
        if(!$this->upload_server){
            throw new \Exception('please set Upload Server');
        }
    }

    public function setGroupId($group_id)
    {
        $this->checkUploadServer();

        $this->upload_server->setGroupId($group_id);
        return $this;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function doRequest()
    {
        $this->checkUploadServer();

        if(!$this->photo_url){
            throw new \Exception('please set photo url');
        }

        $get_upload_server = $this->upload_server->doRequest();
        if (!$get_upload_server) {
            $this->logger->error("not found upload server");
            return false;
        }

        if (!is_object($get_upload_server) && $get_upload_server < 0) {
            return $get_upload_server;
        }

        $post_data = array(
            "user_id" => $this->upload_server->getUserId(),
            "album_id" => $this->upload_server->getAlbumId(),
            "photo" => new CURLFile($this->photo_url, 'image/jfif', basename($this->photo_url))
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->upload_server->getUploadUrl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $vkContent = curl_exec($ch);

        if (curl_errno($ch)) {
            $this->logger->debug("CURL returned error: " . curl_error($ch));
            return false;
        }
        curl_close($ch);

        if (!$vkContent) {
            $this->logger->debug("vkContent is empty");
        }

        $json = json_decode($vkContent);

        if (!$json) {
            return false;
        }

        if (isset($json->server) && $json->server &&
            isset($json->photo) && $json->photo &&
            isset($json->hash) && $json->hash
        ) {
            $this->server = $json->server;
            $this->photo = $json->photo;
            $this->hash = $json->hash;

            return true;
        }

        return false;
    }

}