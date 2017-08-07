<?php
 
include 'mailsystem.php';
//<input type="text" name="name" id="name" value="" class="input" />
//			<label for="name" id="for-name" class="label1" >Name</label><br /><br />
//		<input type="text" name="email" id="email" value="" class="input"/>
//		<label for="email" id="for-email" class="label2">Email</label> <br/><br /><br />	
//		<input type="text" name="subject" id="subject" value="" class="input1" />
//		<label for="subject" id="for-subject" class="label3">Subject</label> <br /><br />		
//		<textarea name="message" id="message" class="textarea" rows="10"></textarea>
//		<label for="message" id="for-message" class="label4">Message</label> 


 
 
 class mailer{
 		
 	function doaction($email,$emailtoname,$body,$subject,$fromname) {
               //var_dump($subject);
	smtp_email($email,$emailtoname,$body,$subject, $fromname, TRUE, FALSE);
  
	
	}
 	
 	
 }
 	
 	
 

  
 
  

?>