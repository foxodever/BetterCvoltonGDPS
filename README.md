# BetterCvoltonGDPS
Better Version of Cvolton's GMDprivateServer

Supported version of Geometry Dash: 1.0 - 2.11 (so any version of Geometry Dash works, as of writing this [February 02, 2020])

Required version of PHP: 5.4+ (tested up to 7.3.11)

### Info
Soon this core gonna be deprecated.
It will happen when i will create my own core
See development: (Soon)

### What this version have?
1) SMTP server! (Email verifying)
2) Password reseting if you forgot it
3) Auto giving creator points
4) Better timestamps
5) Bot attack blocking
6) Better cron
7) Song uploading by file
8) Common password filter
9) Temp mail filter
10) 3 types of captcha (Classic, hCaptcha, reCaptcha)

### Setup
1) Upload the files on a webserver
2) Import database.sql into a MySQL/MariaDB database
3) Edit the links in GeometryDash.exe (some are base64 encoded since 2.1, remember that)
4) Edit files `/config/connection.php` and `/config/mail.php` and `/config/security.php`

### Need help or found bug?
https://discord.gg/8bzEcShgpe

### Credits
Original - https://github.com/Cvolton/GMDprivateServer

Base for account settings and the private messaging system by someguy28

Using this for XOR encryption - https://github.com/sathoro/php-xor-cipher - (incl/lib/XORCipher.php)

Using this for cloud save encryption - https://github.com/defuse/php-encryption - (incl/lib/defuse-crypto.phar)

Jscolor (color picker in packCreate.php) - http://jscolor.com/

Most of the stuff in generateHash.php has been figured out by pavlukivan and Italian APK Downloader, so credits to them
