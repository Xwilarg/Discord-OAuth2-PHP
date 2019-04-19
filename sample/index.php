<?php
require '../src/DiscordOAuth2.php';

// Sample configuration file, contains the following strings:
// clientId: Client ID of the application
// secret: Secret of the application
// url: The redirect URL (URL called after the user is logged in, must be registered in https://discordapp.com/developers/applications/[YourAppId]/oauth)
$auth = json_decode(file_get_contents('token.json'), true);
$oauth2 = new DiscordOAuth2($auth["clientId"], $auth["secret"], $auth["url"]);

if ($oauth2->isRedirected() === false) { // Did the client already logged in ?
    // The parameter can be a combination of the following: connections, email, identity, guilds
    // More information about it here: https://discordapp.com/developers/docs/topics/oauth2#shared-resources-oauth2-scopes
    $oauth2->startRedirection(['identify']);
} else {
    try {
        $answer = $oauth2->getInformation();
        if ($answer["code"] === 0) {
            exit("An error occured: " . $answer["message"]);
        }
        echo "Welcome " . $answer["username"] . "#" . $answer["discriminator"];
    } catch (Exception $e) {
        exit("An error occured: " . $e->getMessage());
    }
}
?>