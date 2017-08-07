<?php

 

header("Content-Type: text/html; charset=utf-8");
date_default_timezone_set("PRC");
include 'class.phpmailer.php';

//require_once '../database/dbconnection.php';

function smtp_email($address,$add,$body,$subject,$name,$html,$affix)
{
//var_dump($subject);
$mail = new PHPMailer(); //建立邮件发送类

$mail->IsSMTP(); // 使用SMTP方式发送
$mail->Host = "smtp.163.com"; // 您的企业邮局域名
$mail->SMTPAuth = true; // 启用SMTP验证功能
$mail->Username = "18291445124@163.com"; // 邮局用户名(请填写完整的email地址)
$mail->Password = "lvchuan123"; // 邮局密码

$mail->From ="18291445124@163.com"; //邮件发送者email地址
$mail->FromName = $name;
$mail->AddAddress($address, $add);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
//$mail->AddReplyTo("", "");

//$mail->AddAttachment("source.rar"); // 添加附件


$mail->Subject = $subject; //邮件标题
$mail->Body =$body; //邮件内容
$mail->AltBody = "没有"; //附加信息，可以省略

if($mail->Send())
return true;
else
	return 
	 
	FALSE;

}

//
//function sendall($body,$html,$affix)
//{
//	$arr=getallemail();
//	for($i=0;$i<count($arr);$i++)
//	{
//		
//		$address=$arr[0];
//		
//	if(	smtp_email($address, $body, $html, $affix))
//	{}
//	else 
//		{
//			echo $address.' '.'false';	
//			
//		}
//		
//	}
//	
//
//	
//}


?>