<?php

namespace VkSdk\Docs\Messages\Includes;

use VkSdk\Docs\Includes\Server;

class UploadMessagesDoc extends GetUploadServer implements Server
{

    /**
     * @var GetUploadServer
     */
    private $upload_server;
    private $file_url;
    private $server;
    private $file;

    /**
     * @return GetUploadServer
     */
    public function getUploadServer()
    {
        return $this->upload_server;
    }

    /**
     * @param GetUploadServer $upload_server
     * @return UploadMessagesDoc
     */
    public function setUploadServer($upload_server)
    {
        $this->upload_server = $upload_server;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFileUrl()
    {
        return $this->file_url;
    }

    /**
     * @param mixed $file_url
     * @return UploadMessagesDoc
     */
    public function setFileUrl($file_url)
    {
        $this->file_url = $file_url;
        return $this;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function checkUploadServer(){
        if(!$this->upload_server){
            throw new \Exception('please set upload server');
        }
    }

    public function doRequest()
    {
        $this->checkUploadServer();

        if(!$this->file_url){
            throw new \Exception('please set file url');
        }

        $get_upload_server = $this->upload_server->doRequest();
        if (!$get_upload_server) {
            $this->logger->error("not found upload server");
            return false;
        }

        $post_data = array(
            "user_id" => $this->upload_server->getUserId(),
            "file" => new \CURLFile($this->file_url, 'application/octet-stream', basename($this->file_url))
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

        if (isset($json->file) && $json->file) {
            $this->file = $json->file;

            return true;
        }

        return false;
    }

}