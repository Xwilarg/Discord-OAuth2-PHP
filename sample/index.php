<?php
require '../src/DiscordOAuth2.php';

// Sample configuration file, contains the following strings:
// clientId: Client ID of the application
// secret: Secret of the application
// url: The redirect URL
$auth = json_decode(file_get_contents('token.json'), true);
$oauth2 = new DiscordOAuth2($auth["clientId"], $auth["secret"], $auth["url"]);

if ($oauth2->isRedirected() === false) {
    $oauth2->startRedirection();
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