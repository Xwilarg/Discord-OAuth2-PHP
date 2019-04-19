<?php
namespace Xwilarg\Discord {
    class OAuth2 {
        function __construct($clientId, $secret, $redirectUrl) {
            $this->_clientId = $clientId;
            $this->_secret = $secret;
            $this->_redirectUrl = $redirectUrl;
            session_start();
        }

        public function startRedirection($scope) {
            $randomString = DiscordOAuth2::generateToken();
            $_SESSION['oauth2state'] = $randomString;
            header('Location: https://discordapp.com/api/oauth2/authorize?client_id=' . $this->_clientId . '&redirect_uri=' . urlencode($this->_redirectUrl) . '&response_type=code&scope=' . join('%20', $scope) . "&state=" . $randomString);
        }

        public function isRedirected() {
            return isset($_GET['code']);
        }

        public function getCustomInformation($endpoint, $forceRefresh = false) {
            return $this->getInformation($forceRefresh, $endpoint);
        }

        public function getUserInformation($forceRefresh = false) {
            return $this->getInformation($forceRefresh, 'users/@me');
        }

        public function getConnectionsInformation($forceRefresh = false) {
            return $this->getInformation($forceRefresh, 'users/@me/connections');
        }

        public function getGuildsInformation($forceRefresh = false) {
            return $this->getInformation($forceRefresh, 'users/@me/guilds');
        }

        private function getInformation($forceRefresh, $endpoint) {
            if ($forceRefresh === true || $this->_accessToken === null) {
                $this->loadToken();
            }
            $curl = curl_init('https://discordapp.com/api/v6/' . $endpoint);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, "false");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $this->_accessToken
            ));
            $response = json_decode(curl_exec($curl), true);
            curl_close($curl);
            return $response;
        }

        private function loadToken() {
            if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
                unset($_SESSION['oauth2state']);
                throw new Exception('Invalid state');
            }
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://discordapp.com/api/v6/oauth2/token",
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "client_id=" . $this->_clientId . "&client_secret=" . $this->_secret . "&grant_type=authorization_code&code=" . $_GET['code'] . "&redirect_uri=" . urlencode($this->_redirectUrl),
                CURLOPT_RETURNTRANSFER => "false"
            ));
            $response = curl_exec($curl);
            $this->_accessToken = json_decode($response, true)['access_token'];
            curl_close($curl);
            return $response;
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
        private $_accessToken = null;
    }
}
?>