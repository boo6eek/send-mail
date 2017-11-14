<?PHP
class multipartmail{
    var $header;
    var $parts;
    var $message;
    var $subject;
    var $to_address;
    var $boundary;
    function multipartmail($dest, $src, $sub){
        $this->to_address = $dest;
        $this->subject = $sub;
        $this->parts = array("");
        $this->boundary = "--" . md5(uniqid(time()));
        $this->header = "From: $src\r\n" .
            "MIME-Version: 1.0\r\n" .
            "Content-Type: multipart/mixed; " .
            " boundary=\"" . $this->boundary . "\"\r\n" .
            "X-Mailer: PHP/" . phpversion();
    }
    function addmessage($msg = "", $ctype = "text/plain"){
        $this->parts[0] = "Content-Type: $ctype; charset=utf-8\r\n" .
            "Content-Transfer-Encoding: 7bit\r\n" .
            "\n" .$msg."\r\n";
    }
    function addattachment($file, $ctype){
        $fname = $file['name'];
        $data = file_get_contents($file['tmp_name']);
        $i = count($this->parts);
        $content_id = "part$i." . sprintf("%09d", crc32($fname)) . strrchr($this->to_address, "@");
        $this->parts[$i] = "Content-Type: $ctype; name=\"$fname\"\r\n" .
            "Content-Transfer-Encoding: base64\r\n" .
            "Content-ID: <$content_id>\r\n" .
            "Content-Disposition: attachment; " .
            " filename=\"$fname\"\r\n" .
            "\n" .
            chunk_split( base64_encode($data), 68, "\n");
        return $content_id;
    }
    function buildmessage(){
        $this->message = "This is a multipart message in mime format.\n";
        $cnt = count($this->parts);
        for($i=0; $i<$cnt; $i++){
            $this->message .= "--" . $this->boundary . "\n" .
                $this->parts[$i];
        }
    }
    /* to get the message body as a string */
    function getmessage(){
        $this->buildmessage();
        return $this->message;
    }
    function sendmail(){
        $this->buildmessage();
        if(mail($this->to_address, $this->subject, $this->message, $this->header)){
            echo 'Пиьмо успешно отправлено!';
        } else {
            echo 'Письмо по каким-то причинам не было отправлено!';
        }
    }
}
?>
