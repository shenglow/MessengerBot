<?php
/**
 * Facebook bot messenger
 *
 * @version 1.0.0
 * @author Boon
 * @since 1.0.0 2019/03/20 Boon: First release
 */

/**
 * Class FacebookBot
 */
class FacebookBot {
    /**
     * @var array $config Setting's array
     */
    private $config;

    /**
     * FacebookBot constructor
     *
     * @return self
     */
    public function __construct()
    {
        $this->config = @include('settings/settings.ini.php');
    }

    /**
     * get provided $section setting in config array
     *
     * @param string $section Section in the config array
     * @param string $key Key in the section
     * @param bool $throwException Use for decide when setting is not set want to throw Exception or not
     *
     * @return mixed Return setting's value
     */
    public function getSetting($section, $key, $throwException = true)
    {
        if (isset($this->config[$section][$key])) {
            return $this->config[$section][$key];
        } else {
            if ($throwException === true) {
                throw new Exception('Setting with section {'.$section.'} value {'.$key.'}');
            } else {
                return false;
            }
        }
    }

    /**
     * set provided $section setting in config array
     *
     * @param string $section Section in the config array
     * @param string $key Key in the section
     * @param mixed $value The value want to set
     *
     * @return void
     */
    public function setSetting($section, $key, $value)
    {
        $this->config[$section][$key] = $value;
    }

    /**
     * check $section has setting
     *
     * @param string $section Section in the config array
     * @param string $key Key in the section
     *
     * @return bool 
     */
    public function hasSetting($section, $key)
    {
        return isset($this->config[$section][$key]);
    }

    /**
     * Handles messages events
     *
     * @param string $sender_psid sender PSID
     * @param array $received_message received message
     *
     * @return void 
     */
    public function handleMessage($sender_psid, $received_message) {
        // Create the payload based on message type
        $response = null;
        if ($received_message['text']) {
            $response = '{
                "text" : "You send the message: '.$received_message['text'].' Now send me an image."
            }';
        } elseif ($received_message['attachments']) {
            $attachment_url = $received_message['attachments'][0]['payload']['url'];
            $response = '{
                "attachment" : {
                    "type" : "template",
                    "payload" : {
                        "template_type" : "generic",
                        "elements" : [{
                            "title" : "Is this the right picture?",
                            "subtitle" : "Tap a button to answer.",
                            "image_url" : "'.$attachment_url.'",
                            "buttons" : [
                                {
                                    "type" : "postback",
                                    "title" : "Yes!",
                                    "payload" : "yes"
                                },
                                {
                                    "type" : "postback",
                                    "title" : "No!",
                                    "payload" : "no"
                                }
                            ]
                        }]
                    }
                }
            }';
        }

        // Sends the response message
        $this->callSendAPI($sender_psid, $response);
    }

    /**
     * Handles messaging_postbacks events
     *
     * @param string $sender_psid sender PSID
     * @param array $received_postback received postback
     *
     * @return void 
     */
    public function handlePostback($sender_psid, $received_postback) {
        $response = null;

        // Set the response based on the postback payload
        switch ($received_postback['payload']) {
            case 'yes':
                $response = '{
                    "text" : "Thanks!"
                }';
                break;
            case 'no':
                $response = '{
                    "text" : "Oops, try sending another image."
                }';
                break;
        }

        // Sends the message to acknowledge the postback
        $this->callSendAPI($sender_psid, $response);
    }

    /**
     * Sends response messages via the Send API
     *
     * @param string $sender_psid sender PSID
     * @param string $response JSON format's response
     *
     * @return void 
     */
    public function callSendAPI($sender_psid, $response) {
        // Construct the message body and Execute the request
        if ($response) {
            $request_body = '{
                "recipient" : {
                    "id" : "'.$sender_psid.'"
                },
                "message" : '.$response.'
            }';

            $messages_api = $this->getSetting('facebook','messages_api');
            $access_token = $this->getSetting('facebook','access_token');

            // API Url
            $url = $messages_api.'?access_token='.$access_token;
            // Initiate cURL.
            $ch = curl_init($url);
            //Tell cURL that we want to send a POST request.
            curl_setopt($ch, CURLOPT_POST, 1);
            //Attach our encoded JSON string to the POST fields.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);
            //Set the content type to application/json
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

            $result = curl_exec($ch);
        }
    }
}

//initial class
$facebook_bot = new FacebookBot();
$verify_token = $facebook_bot->getSetting('facebook','verify_token');

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
            $facebook_bot->handleMessage($sender, $webhook_event['message']);
        } elseif ($webhook_event['postback']) {
            $facebook_bot->handlePostback($sender, $webhook_event['postback']);
        }
    }
} else {
    // Return a '404 Not Found' if event is not form a page subscription
    http_response_code(404);
}
?>