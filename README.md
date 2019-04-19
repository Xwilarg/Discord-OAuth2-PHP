# Discord-OAuth2-PHP

Discord-OAuth2-PHP is a small PHP Discord to use Discord OAuth2.

It doesn't intend to cover all the functionalities of Discord OAuth2 but rather to be lightweight (no dependencies!) and easy to use.<br/>

### How to install it ?
```bash
$ composer require xwilarg/discord-oauth2-php
```

### How to use it ?
At first you need to go in the [Discord developer page](https://discordapp.com/developers/applications/) and create a new application.<br/>
Go in General Information and take your "client id" and "client secret", we will need them later.<br/>
Then go in OAuth2, in "Redirects" press the "Add Redirect" button and enter your redirection URL here (the page when the user will be redirected once he is logged with Discord)

You're ready to write your PHP code now, you can go [here](/sample/index.php) to see an example.

If you have any question, feel free to [open an issue](https://github.com/Xwilarg/Discord-OAuth2-PHP/issues).
