<?php

class ContactController extends Controller
{
    
    public function process(array $params): void 
    {
        if (empty($params))
        {
        $this->header = array(
            "title" => "Kontaktní formulář",
            "keywords" => "kontakt, email",
            "description" => ""
        );
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {        
            //need to have SMPT port
            $mailSender = new MailSender();
            $mailSender->send("vymysleny.mail@gmail.com", $_POST["contact-subject"], $_POST["contact-msg"], $_POST["contact-email"]);        
        }
        $this->view = "Contact/contact";
        }
        else if($params[0] == "terms")
        {
            $this->header["title"] = "Obchodní podmínky";
            $this->view = "Contact/terms";
        }
        else if($params[0] == "personal")
        {
            $this->header["title"] = "Ochrana osobních údajů";
            $this->view = "Contact/personal";
        }
    }

}