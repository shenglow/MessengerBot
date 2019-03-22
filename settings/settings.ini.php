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
                'text' => 'Hello!'
            ),
            array(
                'locale' => 'en_US',
                'text' => 'Messenger Bot testing.'
            )
        )
    )
);
?>