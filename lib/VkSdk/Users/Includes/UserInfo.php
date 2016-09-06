<?php

namespace VkSdk\Users\Includes;

class UserInfo
{

    private $id;
    private $first_name = "";
    private $last_name = "";
    private $sex = 0;
    private $photo;
    private $last_seen = [];

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


    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setLastSeen($time, $platform)
    {
        $this->last_seen['time'] = $time;
        $this->last_seen['platform'] = $platform;
        return $this;
    }

    public function getLastSeen()
    {
        return $this->last_seen;
    }

    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
        return $this;
    }

    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
        return $this;
    }

    public function setSex($sex)
    {
        $this->sex = $sex;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function getSex()
    {
        return $this->sex;
    }

}