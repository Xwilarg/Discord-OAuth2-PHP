<?php
class DiscordOAuth2 {
    function __construct($clientId, $secret, $redirectUrl) {
        $this->_clientId = $clientId;
        $this->_secret = $secret;
        $this->_redirectUrl = $redirectUrl;
        session_start();
    }

    public function startRedirection() {
        $randomString = DiscordOAuth2::generateToken();
        $_SESSION['oauth2state'] = $randomString;
        header('Location: https://discordapp.com/api/oauth2/authorize?client_id=' . $this->_clientId . '&redirect_uri=' . urlencode($this->_redirectUrl) . '&response_type=code&scope=identify' . "&state=" . $randomString);
    }

    private static function generateToken() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLen = strlen($characters);
        $randomString = "";
        for ($i = 0; $i < 20; $i++) {
            $randomString .= $characters[rand(0, $charactersLen - 1)];
        }
        return $randomString;
    }

    private $_clientId;
    private $_secret;
    private $_redirectUrl;
}
?>