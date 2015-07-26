<?php
if($_POST)
{
    $to_email       = "srmuav@gmail.com"; //Recipient email, Replace with own email here
    
    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        
        $output = json_encode(array( //create JSON data
            'type'=>'error', 
            'text' => 'Sorry Request must be Ajax POST'
        ));
        die($output); //exit script outputting json data
    } 
    
    //Sanitize input data using PHP filter_var().
    $user_name      = filter_var($_POST["user_name"], FILTER_SANITIZE_STRING);
    $user_phone     = filter_var($_POST["user_phone"], FILTER_SANITIZE_STRING);
    $user_email     = filter_var($_POST["user_email"], FILTER_SANITIZE_EMAIL);
	$address	     = filter_var($_POST["address"], FILTER_SANITIZE_STRING);
	$department      = filter_var($_POST["department"], FILTER_SANITIZE_STRING);
	$year      = filter_var($_POST["year"], FILTER_SANITIZE_STRING);
	$interest      = filter_var($_POST["interest"], FILTER_SANITIZE_STRING);
	$reason      = filter_var($_POST["reason"], FILTER_SANITIZE_STRING);
   
	
    
    
    //additional php validation
    if(strlen($user_name)<4){ // If length is less than 4 it will output JSON error.
        $output = json_encode(array('type'=>'error', 'text' => 'Name is too short'));
        die($output);
    }
    if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){ //email validation
        $output = json_encode(array('type'=>'error', 'text' => 'Enter a valid email!'));
        die($output);
    }
    
    
    //email body
    $message_body = "\r\nName : ".$user_name."\r\nPhone : ".$user_phone."\r\nEmail Id : ".$user_email."\r\naddress : ".$address."\r\nDepartment : ".$department."\r\nYear : ".$year."\r\nInterest : ".$interest."\r\nReason : ".$reason;
    
    //proceed with PHP email.
    $headers = 'From: '.$user_name.'' . "\r\n" .
    'Reply-To: '.$user_email.'' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    
    $send_mail = mail($to_email, 'Join Us Feedback', $message_body, $headers);
    
    if(!$send_mail)
    {
        //If mail couldn't be sent output error. Check your PHP email configuration (if it ever happens)
        $output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP mail configuration.'));
        die($output);
    }else{
        $output = json_encode(array('type'=>'message', 'text' => 'Hi '.$user_name .' We will get back to you soon.'));
        die($output);
    }
}
?>