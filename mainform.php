<?php
include 'class-mail.php';
require('../wp-load.php'); //library wp
$to = get_option('admin_email'); //email
$blogname = get_option('blogname'); //name
$subject = 'Письмо с сайта '.$blogname; 
$message = '<table>';
foreach ($_REQUEST as $k => $v) {
    $message .= '<tr>';
    $message .= '<td><strong>' . $k . '</strong></td>';
    $message .= '<td>' . $v . '</td>';
    $message .= '</tr>';
}
$message .= '</table>';
$mulmail = new multipartmail($to, $blogname, $subject);
if (isset($_FILES['file'])) {
	if (!empty($_FILES['file']['name'])) {
	    $cid = $mulmail->addattachment($_FILES['file'], $_FILES['file']['type']);
	}
}
$mulmail->addmessage($message, 'text/html');
$mulmail->sendmail();
