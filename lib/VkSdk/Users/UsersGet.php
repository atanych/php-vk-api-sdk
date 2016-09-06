<?php

namespace VkSdk\Users;

use VkSdk\Includes\Request;
use VkSdk\Users\Includes\UserInfo;

/**
 * Class UsersGet
 *
 * @package VkSdk\Users
 */
class UsersGet extends Request
{

    private $user_ids = [];
    private $fields   = [];

    /** @var UserInfo $users_info */
    private $users_info;

    /**
     * @return Includes\UserInfo
     */
    public function getUsersInfo()
    {
        return $this->users_info;
    }

    public function setUserId($user_ids)
    {
        if (is_array($user_ids)) {
            $this->user_ids = array_merge($this->user_ids, $user_ids);
        } else {
            $this->user_ids[] = $user_ids;
        }

        return $this;
    }

    public function setField($field)
    {
        if (is_array($field)) {
            $this->fields = array_merge($this->fields, $field);
        } else {
            $this->fields[] = $field;
        }

        return $this;
    }

    public function doRequest()
    {
        $this->setMethod("users.get");

        if ($this->user_ids) {
            $this->setParameter("user_ids", implode(",", $this->user_ids));
        }
        if ($this->fields) {
            $this->setParameter("fields", implode(",", $this->fields));
        }

        $json = $this->execApi();
        if (!$json) {
            return false;
        }

        if (!is_object($json) && $json < 0) {
            return $json;
        }

        if (isset($json->response) && $json->response) {
            foreach ($json->response as $key => $ui) {
                $this->users_info = new UserInfo();
                $this->users_info->setId($ui->id);
                $this->users_info->setFirstName($ui->first_name);
                $this->users_info->setLastName($ui->last_name);
                if (isset($ui->photo_200)) {
                    $this->users_info->setPhoto($ui->photo_200);
                }
            }

            return true;
        }

        return false;
    }

}