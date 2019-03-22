<?php
/**
 * Facebook bot messenger's Messages modules
 *
 * @version 1.0.0
 * @author Boon
 * @since 1.0.0 2019/03/20 Boon: First release
 */

/**
 * Class Messages
 */
class Messages extends FacebookBot {
    /**
     * @var string $api Messages api url
     * @var string $access_token Page access token
     */
    private $messages_api;
    private $access_token;

    /**
     * Messages constructor
     *
     * @return self
     */
    public function __construct()
    {
    	parent::__construct();
        $this->messages_api = $this->getSetting('api','messages');
        $this->access_token = $this->getSetting('token','access');
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


            // API Url
            $url = $this->messages_api.'?access_token='.$this->access_token;
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
?>