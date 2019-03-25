<?php
return array(
    'token' => array(
        'access' => 'EAADnKLFGFZBsBALls7vZCJEZA6mDJlrL0xXlEg1SlIJcqBhRRz9rJlroN41DZAsma3sA4PSXJox9dVKWNlCK89RpNzSyDKDCxzoyflZCNvVeJ3UuhQoSmpIEPnck4Pzdo94ZBoLZC5UQTcnPgESPvVMVZBIEB2vVx088MdXvaCfZBAAZDZD',
        'verify' => 'messenger_test'
    ),
    'api' => array(
        'messages' => 'https://graph.facebook.com/v2.6/me/messages',
        'messenger_profile' => 'https://graph.facebook.com/v2.6/me/messenger_profile'
    ),
    'messenger_profile' => array(
        'get_started' => 'Get Started',
        'greeting' => array(
            array(
                'locale' => 'default',
                'text' => 'Hello, {{user_full_name}}'
            ),
            array(
                'locale' => 'en_US',
                'text' => 'Hello, {{user_full_name}}. This is Messenger Bot testing page.'
            )
        ),
        'persistent_menu' => array(
            array(
                'locale' => 'default',
                'composer_input_disabled' => 'true',
                'call_to_actions' => array(
                    array(
                        'title' => 'My Account',
                        'type' => 'nested',
                        'call_to_actions' => array(
                            array(
                                'title' => 'Pay Bill',
                                'type' => 'postback',
                                'payload' => 'PAYBILL_PAYLOAD'
                            ),
                            array(
                                'title' => 'History',
                                'type' => 'postback',
                                'payload' => 'HISTORY_PAYLOAD'
                            ),
                            array(
                                'title' => 'Contact Info',
                                'type' => 'postback',
                                'payload' => 'CONTACT_INFO_PAYLOAD'
                            )
                        )
                    ),
                    array(
                        'type' => 'web_url',
                        'title' => 'Latest News',
                        'url' => 'http://www.messenger.com',
                        'webview_height_ratio' => 'full'
                    )
                )    
            ),
            array(
                'locale' => 'zh_CN',
                'composer_input_disabled' => 'false',
                'call_to_actions' => array(
                    array(
                        'title' => 'Pay Bill',
                        'type' => 'postback',
                        'payload' => 'PAYBILL_PAYLOAD'
                    )
                )
            )
        )
    )
);
?>