<?php

namespace VkSdk\Docs\Messages;

use VkSdk\Docs\Includes\Doc;
use VkSdk\Docs\Messages\Includes\UploadMessagesDoc;

class SaveMessagesDoc extends UploadMessagesDoc implements Doc
{

    /**
     * @var UploadMessagesDoc
     */
    private $vk_upload_file;

    private $media_id;
    private $owner_id;

    /**
     * @return UploadMessagesDoc
     */
    public function getVkUploadFile()
    {
        return $this->vk_upload_file;
    }

    /**
     * @param UploadMessagesDoc $vk_upload_file
     * @return SaveMessagesDoc
     */
    public function setVkUploadFile($vk_upload_file)
    {
        $this->vk_upload_file = $vk_upload_file;
        return $this;
    }

    public function getOwnerId()
    {
        return $this->owner_id;
    }

    public function getMediaId()
    {
        return $this->media_id;
    }

    public function checkUploadDoc(){
        if(!$this->vk_upload_file){
            throw new \Exception('please set upload file');
        }
    }
    
    public function doRequest()
    {
        $this->setMethod("docs.save");

        $this->checkUploadDoc();
        
        $vk_upload = $this->vk_upload_file->doRequest();

        if (!$vk_upload) {
            $this->logger->error("upload wall pdf result is empty");
            return false;
        }

        $this->setParameter("file", $this->vk_upload_file->getFile());

        $json = $this->execApi();
        if (!$json) {
            return false;
        }

        if (isset($json->response) && $json->response && isset($json->response[0]) && $json->response[0] &&
            isset($json->response[0]->id) && $json->response[0]->id &&
            isset($json->response[0]->owner_id) && $json->response[0]->owner_id
        ) {
            $this->media_id = $json->response[0]->id;
            $this->owner_id = $json->response[0]->owner_id;

            return true;
        }

        return false;
    }

}