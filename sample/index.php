<?php

require __DIR__ . '/vendor/autoload.php';

use Xwilarg\Discord\OAuth2;

// Sample configuration file, contains the following strings:
// clientId: Client ID of the application
// secret: Secret of the application
// url: The redirect URL (URL called after the user is logged in, must be registered in https://discordapp.com/developers/applications/[YourAppId]/oauth)
$auth = json_decode(file_get_contents('token.json'), true);
$oauth2 = new OAuth2($auth["clientId"], $auth["secret"], $auth["url"]);

if ($oauth2->isRedirected() === false) { // Did the client already logged in ?
    // The parameter can be a combination of the following: connections, email, identity or guilds
    // More information about it here: https://discordapp.com/developers/docs/topics/oauth2#shared-resources-oauth2-scopes
    $oauth2->startRedirection(['identify', 'connections']);
} else {
    // If preload the token to see if everything happen without error
    $ok = $oauth2->loadToken();
    if ($ok !== true) {
        // A common error can be to reload the page because the code returned by Discord would still be present in the URL
        // If this happen, isRedirected will return true and we will come here with an invalid code
        // So if there is a problem, we redirect the user to Discord authentification
        $oauth2->startRedirection(['identify', 'connections']);
    } else {
        // ---------- USER INFORMATION
        $answer = $oauth2->getUserInformation(); // Same as $oauth2->getCustomInformation('users/@me')
        if (array_key_exists("code", $answer)) {
            exit("An error occured: " . $answer["message"]);
        } else {
            echo "Welcome " . $answer["username"] . "#" . $answer["discriminator"];
        }

        echo '<br/><br/>';
        // ---------- CONNECTIONS INFORMATION
        $answer = $oauth2->getConnectionsInformation();
        if (array_key_exists("code", $answer)) {
            exit("An error occured: " . $answer["message"]);
        } else {
            foreach ($answer as $a) {
                echo $a["type"] . ': ' . $a["name"] . '<br/>';
            }
        }
    }
}
?>