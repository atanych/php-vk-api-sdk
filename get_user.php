<?php
require 'vendor/autoload.php';

class Logger extends \Psr\Log\AbstractLogger
{
    public $log_file;

    public function __construct($file) {
        $this->log_file = $file;
    }

    public function log($level, $message, array $context = [])
    {
        file_put_contents($this->log_file, '[' . date("Y-m-d H:i:s") . '] php.' . $level . " " . $message . "\n", FILE_APPEND);
    }
}
$at = '7a13fd1be0f953d2223d0306b032fd39bb9e9282b9c5a045827d460921065eb25fac4481722c4bf766937';
$request = new \VkSdk\Users\UsersGet(null, new Logger("new_logger"));
$request->setUserId(1025529);
$request->setField('photo_200');
$request->doRequest();
//$result = $request->getUsersInfo();
//print_r($result->getFirstName());
//echo PHP_EOL;
//
//print_r($result->getLastName());
//echo PHP_EOL;
//
//
//print_r($result->getPhoto());
//echo PHP_EOL;
