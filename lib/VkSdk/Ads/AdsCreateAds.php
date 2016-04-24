<?php

namespace VkSdk\Ads;

use VkSdk\Ads\Includes\AdsAdSpecification;
use VkSdk\Ads\Includes\Specifications;
use VkSdk\Includes\Request;

class AdsCreateAds extends Request
{

    private $account_id;

    private $ad_specification = array();

    private $ids = array();

    public function getIds()
    {
        return $this->ids;
    }

    public function setAccountId($account_id)
    {
        $this->account_id = $account_id;
        return $this;
    }

    public function addAdSpecification(AdsAdSpecification $adsAdSpecification)
    {
        return $this->ad_specification[] = $adsAdSpecification;
    }

    private function adSpecificationsToJSON()
    {
        $ad = array();

        foreach ($this->ad_specification as $as) {
            $ad[] = $as->getArray();
        }

        return json_encode($ad);
    }

    public function doRequest()
    {

        if (!$this->account_id) {
            throw new \Exception("not fill account_id");
        }

        $this->setMethod("Ads.createAds");

        $this->setParameter("account_id", $this->account_id);
        $this->setParameter("data", $this->adSpecificationsToJSON());

        $json = $this->execApi();
        if (!$json) {
            return false;
        }

        if (!is_object($json) && $json < 0) {
            return $json;
        }

        if (
            isset($json->response) && $json->response &&
            isset($json->response[0]) && $json->response[0] &&
            isset($json->response[0]->id) && $json->response[0]->id
        ) {
            foreach ($json->response as $rs) {
                $this->ids[] = $rs->id;
            }

            return true;
        }

        return false;
    }

}