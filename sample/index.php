<?php
require '../src/DiscordOAuth2.php';

// Sample configuration file, contains the following strings:
// clientId: Client ID of the application
// secret: Secret of the application
// url: The redirect URL
$auth = json_decode(file_get_contents('token.json'), true);
$oauth2 = new DiscordOAuth2($auth["clientId"], $auth["secret"], $auth["url"]);
$oauth2->startRedirection();
?>