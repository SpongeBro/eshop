<?php

class MailSender
{
    
    public function send(string $to, string $subject, string $message, string $from) : bool
    {
        $header = "From: ".$from;
        $header .= "\nMINE-Version: 1.0\n";
        $header .= "Content-Type: text/html; charset=\"utf-8\"\n";
        return mb_send_mail($to, $subject, $message, $header);
    }
}