<?php

namespace VkSdk\Messages;

use VkSdk\Includes\Request;
use VkSdk\Messages\Includes\MessageItem;

class MessageGetById extends Request
{

    private $message_ids;

    private $body;
    private $uid;
    private $photo;
    private $audio;
    private $video;
    private $coords;

    /**
     * @return mixed
     */
    public function getAudio()
    {
        return $this->audio;
    }

    /**
     * @param mixed $audio
     */
    public function setAudio($audio)
    {
        $this->audio = $audio;
    }

    /**
     * @return mixed
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param mixed $video
     */
    public function setVideo($video)
    {
        $this->video = $video;
    }

    /**
     * @return mixed
     */
    public function getCoords()
    {
        return $this->coords;
    }

    /**
     * @param mixed $coords
     */
    public function setCoords($coords)
    {
        $this->coords = $coords;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    public function setMessageId($message_id)
    {
        return $this->message_ids = $message_id;
    }

    public function doRequest()
    {
        $this->setMethod("messages.getById");
        if ($this->message_ids) {
            $this->setParameter("message_ids", $this->message_ids);
        }

        $json = $this->execApi();
        if (!$json) {
            return false;
        }

        if (!is_object($json) && $json < 0) {
            return $json;
        }
        if (
            isset($json->response) && !empty($json->response->items)
        ) {
            $response = $json->response->items[0];
            $this->setBody($response->body);
            $this->setUid($response->user_id);
            if (!empty($response->attachments)) {
                foreach ($response->attachments as $attachment) {
                    switch ($attachment->type) {
                        case 'photo':
                            if (isset($attachment->photo->photo_807)) {
                                $photo = $attachment->photo->photo_807;
                            } else if (isset($attachment->photo->photo_604)) {
                                $photo = $attachment->photo->photo_604;
                            } else {
                                $photo = $attachment->photo->photo_130;
                            }
                            $this->setPhoto($photo);
                            break;
                        case 'audio':
                            $this->setAudio($attachment->audio->url);
                            break;
                        case 'video':
                            $this->setVideo($attachment->video->photo_320);
                            break;
                    }
                }
            }
            if (!empty($response->geo)) {
                $this->setCoords($response->geo->coordinates);
            }
            return true;
        }

        return false;
    }

}