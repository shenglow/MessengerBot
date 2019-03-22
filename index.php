<?php
include_once('modules/facebookbot.php');
include_once('modules/messages.php');

//initial class
$messages = new Messages();
$verify_token = $messages->getSetting('token','verify');

$challenge = (isset($_REQUEST['hub_challenge'])) ? $_REQUEST['hub_challenge'] : null;
$hub_verify_token = (isset($_REQUEST['hub_verify_token'])) ? $_REQUEST['hub_verify_token'] : null;

// check token
if ($hub_verify_token === $verify_token) {
    echo $challenge;
}

$httpRawData = file_get_contents('php://input');
$input = json_decode($httpRawData, true);

// Check the webhook event is from a Page subscription
if ($input['object'] === 'page') {
    foreach ($input['entry'] as $entry) {
        // Gets the body of the webhook event
        $webhook_event = $entry['messaging'][0];
        // Get the sender PSID
        $sender = $webhook_event['sender']['id'];
        if ($webhook_event['message']) {
            $messages->handleMessage($sender, $webhook_event['message']);
        } elseif ($webhook_event['postback']) {
            $messages->handlePostback($sender, $webhook_event['postback']);
        }
    }
}
?>