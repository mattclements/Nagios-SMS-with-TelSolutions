<?php

require('telsolutions-php/textportalv2.php');

// Settings
$username   = 'YOURUSERNAME';
$secret     = 'YOURSECRET';
        
// Sender ID for messages (Maximum 11 characters alpha-numeric only)
$senderId	=	'YOURSENDER';

// Connect to API over HTTPS? (Set to False if php_openssl isn't present)
$ssl = true;

/* End Configs */

# Get the Argvs
$phone  = $argv[1];
$msg    = $argv[2];
$msg    = str_replace('\n', "\n", $msg);


$client = new TextPortal($username, $secret, $senderId, $ssl);

$return = $client->send($phone, $msg);

if($return!=="05")
{
	echo "Error Sending SMS:\n";
	var_dump($return);
}