<?php
require 'vendor/autoload.php';
$at = '7a13fd1be0f953d2223d0306b032fd39bb9e9282b9c5a045827d460921065eb25fac4481722c4bf766937';
$MESSAGE = 4;

$request = new \VkSdk\Messages\MessagesGetLongPollServer($at);
$request->doRequest();
