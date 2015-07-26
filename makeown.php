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
	$type	        = filter_var($_POST["type"], FILTER_SANITIZE_STRING);
	$range      = filter_var($_POST["range"], FILTER_SANITIZE_STRING);
	$application      = filter_var($_POST["application"], FILTER_SANITIZE_STRING);
	$camera      = filter_var($_POST["camera"], FILTER_SANITIZE_STRING);
	$flyingtime      = filter_var($_POST["flyingtime"], FILTER_SANITIZE_STRING);
	$altitude      = filter_var($_POST["altitude"], FILTER_SANITIZE_STRING);
	$controller      = filter_var($_POST["controller"], FILTER_SANITIZE_STRING);
	$budget        = filter_var($_POST["budget"], FILTER_SANITIZE_STRING);
    $user_name      = filter_var($_POST["user_name"], FILTER_SANITIZE_STRING);
    $user_email     = filter_var($_POST["user_email"], FILTER_SANITIZE_EMAIL);
	
    
    
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
    $message_body = "\r\nType : ".$type."\r\nRange : ".$range."\r\nApplication : ".$application."\r\nCamera : ".$camera."\r\n Flying Time : ".$flyingtime." min\r\nAltitude : ".$altitude." metres\r\nGround Controller : ".$controller."\r\nBudget : ".$budget."\r\nName : ".$user_name."\r\nEmail : ".$user_email;
    
    //proceed with PHP email.
    $headers = 'From: '.$user_name.'' . "\r\n" .
    'Reply-To: '.$user_email.'' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    
    $send_mail = mail($to_email, 'Make Your Own UAV Feedback', $message_body, $headers);
    
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