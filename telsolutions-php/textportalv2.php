<?php
/*
 * Text Portal API Sending Class v2
 * --------------------------------
 * 
 * This class allows you to send a text message through the Text Portal
 * API by providing the phone number and message content. You can send a
 * one-2-one or one-2-many message and retrieve statuses for one-2-many.
 * 
 * Please note you must update the $username, $secret and $senderId with
 * your own login information and desired Sender ID.
 * 
 * You must have php_openssl.dll installed to connect to the API over HTTPS.
 * Set $ssl to false if you want to send over HTTP.
 *
 */

class TextPortal {


    // Settings
    private $username   = '';
    private $secret     = '';
        
    // Sender ID for messages (Maximum 11 characters alpha-numeric only)
    private $senderId	=	'';
        
    // Connect to API over HTTPS?
    private $ssl = true;
	
	
	public function __construct($username,$secret,$senderId='',$ssl=true)
	{
		$this->username = $username;
		$this->secret = $secret;
		$this->senderId = $senderId;
		$this->ssl = $ssl;
	}
        
        
    // Send function
    public function send($number = '', $message = '', $multiple = false) {
        
        $extra = '';
                
        if($multiple) $extra = "&broadcast=multiple";
        
        $data = "username=".$this->username."&secret=".$this->secret."&senderid=".$this->senderId."&number=".$number."&message=".$message.$extra;
			
        return $this->post($data);		
        
    }


    // Post function
    public function post($data) {

        // Determine protocol
        if($this->ssl == true) $protocol = "https://";
        else $protocol = "http://";

        // Build URL
        $url = $protocol . "www.textportal.co.uk/api";

        // Build request
        $params =
			array('http' =>
				array(
					'method' => 'POST',
					'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
					                . "Content-Length: " . strlen($data) . "\r\n",
					'content' => $data
				)
			);

        // Create stream context
        $ctx = stream_context_create($params);

        // Open URL with stream    
        $fp = fopen($url, 'rb', false, $ctx);

        // If failed exception   
        if(!$fp) throw new Exception("Problem with ".$url.", " . $php_errormsg);

        // Get response   
        $response = @stream_get_contents($fp);
        
        // If no response exception    
        if($response === false) throw new Exception("Problem reading data from $url, $php_errormsg");

        // Return response
        return $response;
        
    }


}