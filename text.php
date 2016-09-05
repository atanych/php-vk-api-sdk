<?php
require 'vendor/autoload.php';
$at = '7a13fd1be0f953d2223d0306b032fd39bb9e9282b9c5a045827d460921065eb25fac4481722c4bf766937';
$server = new \VkSdk\Photos\Messages\Includes\GetMessagesUploadServer($at);
$root   = new \VkSdk\Photos\Messages\SaveMessagesPhoto($at);
$f      = new \VkSdk\Photos\Messages\Includes\UploadMessagesPhoto($at);
$f->setUploadServer($server);
$f->setPhotoUrl('./download.jpg');
$root->setVkUploadPhoto($f)->doRequest();
$attach = new \VkSdk\Messages\Includes\MessagesAttachments();
$attach->setType('photo');
$attach->setOwnerId($root->getOwnerId());
$attach->setMediaId($root->getMediaId());
$message = new \VkSdk\Messages\MessagesSend($at);
$result  = $message->setMessage("NICE")
    ->setUserId(36950380);
$result->addAttachment($attach);
$result->doRequest();