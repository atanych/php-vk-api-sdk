<?php

namespace VkSdk\Includes;


use Psr\Log\LoggerInterface;
use VkSdk\Config\Config;
use VkSdk\Logger\Logger;

abstract class Request extends \ApiRator\Includes\Request implements VkInterface
{

    private $error_code;
    private $error_msg;
    private $response;
    private $access_token;

    public function __construct($access_token, LoggerInterface $loggerInterface = null)
    {
        $this->access_token = $access_token;
        if (!$loggerInterface) {
            $loggerInterface = new Logger();
        }
        parent::__construct('vkarg', $loggerInterface);
    }

    public function getErrorCode()
    {
        return $this->error_code;
    }

    public function getErrorMsg()
    {
        return $this->error_msg;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function answerProcessing($content)
    {
        $json = json_decode($content);

        if (isset($json->response)) {
            $this->response = $json->response;
        }
        if (isset($json->error) && $json->error) {
            if (isset($json->error->error_code) && $json->error->error_code) {
                if ($json->error->error_code == 14) {
                    /*
                    if( $need_captcha_response ){
                        if( is_object( $json ) && isset( $json->error->captcha_sid ) && isset( $json->error->captcha_img ) ){
                            $recognize = new AntigateRecognizeCaptchaRequest( $json->error->captcha_img );
                            $recognize->downloadCaptcha();
                            $result = $recognize->doRecognize();
                            if( !$result ) {
                                $this->logger->debug("Don't recognize captcha!");
                                return false;
                            }
                            $this->setParameter( "captcha_sid", $json->error->captcha_sid );
                            $this->setParameter( "captcha_key", $recognize->getRecognizeCaptcha() );
                            $this->logger->debug("Send captcha_sid " . $json->error->captcha_sid);
                            $this->logger->debug("Send captcha_key " . $recognize->getRecognizeCaptcha());
                        }
                        else{
                            $this->logger->debug("Json is corrupt: " . serialize( $json ));
                            return false;
                        }
                    }
                    */
                }
                $this->error_code = $json->error->error_code;
            }
            if (isset($json->error->error_msg) && $json->error->error_msg) {
                $this->error_msg = $json->error->error_msg;
                $this->logger->error($this->error_msg);
            }

            return -1;
        }

        return $json;
    }

    public function getResultApiUrl()
    {
        $version = $this->getApiVersion();
        if (!$version) {
            $version = self::API_VERSION;
        }

        $url = self::API_URL . $this->getMethod() . "?v=" . $version . "&access_token=" . $this->access_token;

        return $url;
    }

    abstract public function doRequest();

}