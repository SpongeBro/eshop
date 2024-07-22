<?php
//ADMIN:
//admin
//admin123

//OTHER:
//pass: Username123

class AccountController extends Controller
{
    public function process(array $params): void 
    {        
        //account profile
        if (empty($params))
        {
            if (!isset($_SESSION["user"]))
                $this->reroute("account/login");
            
            $this->header["title"] = "Uživatelský profil";            
            
            $idUser = $_SESSION["user"]["idUser"];
            
            //which panel to display to user
            $panel = isset($_GET["p"]) ? $_GET["p"] : 0;            
            switch($panel)
            {
                //display homepage (orders, points)
                case 0:                     
                    $this->view = "Account/index";
                    $this->showOrders($idUser);    
                    break;
                //display order detail
                case 1: 
                    $this->view = "Account/orderdetail";
                    $o = isset($_GET["o"]) ? $_GET["o"] : 0;
                    $this->getOrderDetail($idUser, $o);
                    break;
                //display user info
                case 2:
                    $this->view = "Account/detail";                    
                    break;
                //change password
                case 3:
                    $this->view = "Account/password";
                    break; 
                //delete profile
                case 4:
                    $this->deleteUser($idUser);  
                    break;
                default:
                    $this->reroute("error");
                    break;
            }
        }
        
        else if ($params[0] == "login" && $_SERVER["REQUEST_METHOD"] == "POST")
        {
            $logged = $this->login($_POST["username"], $_POST["password"]);
            if (!$logged)
            {                
                $this->header["title"] = "Přihlašovací formulář";
                $this->view = "Account/login";
                //to show credentials were incorrect
                $this->data["logged"] = false;
            }
                
            else $this->reroute("home");
        }
        
        else if ($params[0] == "login" && !isset($_SESSION["user"]))
        {
            $this->header["title"] = "Přihlašovací formulář";
            $this->view = "Account/login";
        }
        
        else if ($params[0] == "logout" && isset($_SESSION["user"]))
        {
            $this->logout();    
            $this->reroute("home");
        }        
        
        else if ($params[0] == "register" && $_SERVER["REQUEST_METHOD"] == "POST")
        {            
            //set cart ID to already stored cart ID, otherwise set to 0
            //$cartId = isset($_SESSION["cartId"]) ? $_SESSION["cartId"] : 0;
            $input = array(
                "username" => $_POST["username"], 
                "password" => $_POST["password"], 
                "password_again" => $_POST["password_again"],
                "name" => $_POST["name"], 
                "surname" => $_POST["surname"], 
                "email" => $_POST["email"], 
                "address" => $_POST["address"], 
                "city" => $_POST["city"],
                "psc" => $_POST["psc"],
                "phone" => $_POST["phone"]);
            
            if(!$this->register($input)) //register unsucessfull -> return same register form with already filled data
                $this->view = "Account/register";
            
            else $this->reroute("home");                        
        }
        
        else if ($params[0] == "register" && !isset($_SESSION["user"]))
        {
            $this->header["title"] = "Registrační formulář";
            $this->view = "Account/register";
        }
        
        else if ($params[0] == "edit" && $_SERVER["REQUEST_METHOD"] == "POST")
        {
            if (!isset($_SESSION["user"])) $this->reroute ("home");
            
            $idUser = $_SESSION["user"]["idUser"];
            $input = array(
                "name" => $_POST["name"], 
                "surname" => $_POST["surname"], 
                "email" => $_POST["email"], 
                "address" => $_POST["address"], 
                "city" => $_POST["city"],
                "psc" => $_POST["psc"],
                "phone" => $_POST["phone"]);
            
            //invalid -> return to account edit with inserted values
            if(!$this->edit($idUser, $input))                            
                $this->view = "Account/detail";            
            //if user is editing from cart -> return to cart               
            else if (isset($_POST["delivery_edit"])) 
                    $this->reroute("cart?sum");
            else
                $this->reroute("account");
        }
        
        else if ($params[0] == "password" && $_SERVER["REQUEST_METHOD"] == "POST")
        {
           if (!isset($_SESSION["user"])) $this->reroute ("home");
            
            $username = $_SESSION["user"]["username"];
            if (!$this->changePassword($username, $_POST["password_old"], $_POST["password"], $_POST["password_again"]))
                    $this->view = "Account/password";
            
            else $this->reroute("account");            
        }
        
        else $this->reroute("home");
    }
    
    private function login(string $username, string $password) : bool
    {
        $username = $this->testInput($username);
        $password = $this->testInput($password);
        $am = new AccountManager;
        $user = $am->getUser($username, $password);
        if ($user) //login succesful
        {
            unset($user["password"]);
            $_SESSION["user"] = $user;
            return true;
        }
        return false;
    }
    
    private function logout()
    {
       //session_destroy();
       unset($_SESSION["user"]);        
    }
    
    private function register(array $input) : bool
    {
            //remove html tags from input
            foreach ($input as &$value)
                $value = $this->testInput($value); 
            unset($value);
            
            //check minimum requirements for password
            if (!preg_match("/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/", $input["password"]))
            {
                $this->data["msg_pass_invalid"] = "Heslo neni validní.";
                return false;
            }
            
            //check if second password matches
            if ($input["password"] != $input["password_again"])
            {
                $this->data["msg_pass_notmatch"] = "Heslo se musí shodovat.";
                return false;
            }
            
            //check if username already exists
            $am = new AccountManager();
            $u = $am->findUserByUsername($input["username"]);
            if ($u)
            {
                $this->data["msg_user_exists"] = "Uživatelské jméno již existuje.";
                return false;
            }
            
            //check if email is already registered
            $u = $am->findUserByEmail($input["email"]);
            if ($u)
            {
                $this->data["msg_email_invalid"] = "Emailová adresa je již zaregistrovaná.";
                return false;
            }
            
            //check other info validity
            $info = array(
              "name" => $input["name"],
              "surname" => $input["surname"],
              "email" => $input["email"],
              "psc" => $input["psc"],
              "phone" => $input["phone"]
            );         
            if (!$this->validateUserInfo($info))
                return false;
            
            //ADD USER TO DATABASE AND LOGIN
            $am->addUser($input);
            $newUser = $am->getUser($input["username"], $input["password"]);
            unset($newUser["password"]);
            $_SESSION["user"] = $newUser;     
            
            return true;
    }
         
    //update user details
    private function edit(int $idUser, array $input) : bool
    {
        foreach ($input as &$value)
                $value = $this->testInput($value); 
            unset($value);
            
        $am = new AccountManager();
        $u = $am->findUserByEmail($input["email"]);
        //check if email is not registered by another user
        if ($u && $u["idUser"] != $idUser)
        {
            $this->data["msg_email_invalid"] = "Emailová adresa je již zaregistrovaná.";
            return false;
        }    
        
        if (!$this->validateUserInfo($input))
            return false;
        
        //UPDATE USER PERSONAL INFO AND LOAD NEW DATA INTO SESSION
        $am->updateUser($idUser, $input);
        $newU = $am->getUserById($idUser);
        unset($newU["password"]);
        $_SESSION["user"] = $newU;
        return true;     
    }
    
    private function changePassword(string $username, string $oldpass, string $pass, string $pass_again) : bool
    {
        $oldpass = $this->testInput($oldpass);
        $pass = $this->testInput($pass);
        $pass_again = $this->testInput($pass_again);                
        $am = new AccountManager();
        $u = $am->getUser($username, $oldpass);
        //check if old password is correct
        if (!$u) 
        {
            $this->data["msg_pass_old_notmatch"] = "Heslo je nesprávné.";
            return false;                    
        }
        if (!preg_match("/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/", $pass))
        {
            $this->data["msg_pass_invalid"] = "Heslo neni validní.";
            return false;
        }  
        if ($pass != $pass_again)
        {
            $this->data["msg_pass_notmatch"] = "Heslo se musí shodovat.";
            return false;
        }
        $am->changePassword($username, $pass);
        return true;
    }
    
    private function validateUserInfo(array $info) : bool
    {
        if(!preg_match("/[A-ZÁ-Ž][a-zá-ž]{2,}/", $info["name"]))
        {
            $this->data["msg_name_invalid"] = "Jméno neni validní.";
            return false;                    
        }
        if(!preg_match("/[A-ZÁ-Ž][a-zá-ž]{2,}/", $info["surname"]))
        {
            $this->data["msg_surname_invalid"] = "Příjmení neni validní.";
            return false;
        }
        if(!preg_match("/^[A-Za-z0-9]+(.[A-Za-z0-9]+)?@[A-Za-z]+.(com|cz)$/", $info["email"]))
        {
            $this->data["msg_email_invalid"] = "Email neni validní.";
            return false;
        }
        if(!preg_match("/^[0-9]{5}$/", $info["psc"]))
        {
            $this->data["msg_psc_invalid"] = "PSČ neni validní.";
            return false;
        }
        if(!preg_match("/^[0-9]{9}$/", $info["phone"]))
        {
            $this->data["msg_phone_invalid"] = "Telefon neni validní.";
            return false;
        }
        return true;
    }
    
    private function deleteUser(int $idUser)
    {
        $am = new AccountManager();
        $am->deleteUser($idUser);
        unset($_SESSION["user"]);
        $this->reroute("home");
    }
    
    private function showOrders(int $idUser)
    {
        $om = new OrderManager();
        $orders = $om->getAllOrdersFromUserId($idUser);
        $this->data["orders"] = $orders;
    }
    
    private function getOrderDetail($idUser, $idOrder)
    {
        $om = new OrderManager();
        $order = $om->getOrderFromUserId($idUser, $idOrder);        
        $this->data["order"] = $order;
        if (!$order) return;
        $this->data["products"] = $om->getOrderProducts($idOrder);        
    }

}

