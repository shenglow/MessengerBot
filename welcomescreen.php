<?php
include_once('modules/facebookbot.php');
include_once('modules/messengerprofile.php');

//initial class
$messengerProfile = new MessengerProfile();

$messengerProfile->setGetStartedButton();
$messengerProfile->setGreetingText();
$messengerProfile->setPersistentMenu();
?>