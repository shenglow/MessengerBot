<?php
/**
 * Facebook bot messenger's messenger profile modules
 *
 * @version 1.0.0
 * @author Boon
 * @since 1.0.0 2019/03/22 Boon: First release
 */

/**
 * Class MessengerProfile
 */
class MessengerProfile extends FacebookBot {
    /**
     * @var string $api Messenger profile api url
     * @var string $access_token Page access token
     */
    private $messenger_profile_api;
    private $access_token;

    /**
     * MessengerProfile constructor
     *
     * @return self
     */
    public function __construct()
    {
    	parent::__construct();
        $this->messenger_profile_api = $this->getSetting('api','messenger_profile');
        $this->access_token = $this->getSetting('token','access');
    }

    /**
     * Setting the Get Started Button Postback
     *
     * @return void
     */
    public function setGetStartedButton() {
        $request_body = '{
            "get_started" : {
                "payload" : "'.$this->getSetting('messenger_profile','get_started').'"
            }
        }';

        // Send Constructed request body
        $this->callMessengerProfileAPI($request_body);
    }

    /**
     * Setting the Greeting Text
     *
     * @return void
     */
    public function setGreetingText() {
        $arr_greeting = $this->getSetting('messenger_profile','greeting');
        $request_body = '{"greeting" : [';
        foreach ($arr_greeting as $value) {
            $request_body .= '{
                "locale" : "'.$value["locale"].'",
                "text" : "'.$value["text"].'"
            },';
        }
        $request_body .= ']}';

        // Send Constructed request body
        $this->callMessengerProfileAPI($request_body);
    }

    /**
     * Sends Constructed request body via the Messenger Profile API
     *
     * @param string $request_body JSON format's request body
     *
     * @return void
     */
    public function callMessengerProfileAPI($request_body) {
        // Execute the request
        if ($request_body) {
            // API Url
            $url = $this->messenger_profile_api.'?access_token='.$this->access_token;
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