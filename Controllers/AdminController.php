<?php

class AdminController extends Controller
{    
    public function process(array $params): void 
    {
        if(!isset($_SESSION["user"]) || $_SESSION["user"]["role"] != "admin")
            $this->reroute("home");

        $this->header["title"] = "Administrační rozhraní";      
        $this->view = "Admin/index";                            
        
        if (empty($params)) return;
        
        else if ($params[0] == "manageProduct" && $_SERVER["REQUEST_METHOD"] == "POST")
        {
            $input = array(                   
                "idPlatform" => $_POST["idPlatform"],
                "url" => $_POST["url"],
                "name" => $_POST["name"],
                "genre" => $_POST["genre"],
                "game_type" => $_POST["game_type"],
                "price" => $_POST["price"],
                "stock" => $_POST["stock"],
                "description" => $_POST["description"]
            );    
            //add new product
            if ($_POST["action"] == "create")
            {                
                $this->addProduct($input);
                $this->data["action"] = $input["name"]." přidán.";
                //$this->reroute("admin");
            }
            //update product info
            else if ($_POST["action"] == "update" && !empty($_POST["idProduct"]))
            {
                $this->updateProduct($_POST["idProduct"], $input);
                $this->data["action"] = $input["name"]." aktualizován.";
                //$this->reroute("admin");
            }
        }
        //add product FORM
        else if ($params[0] == "add")
        {
            $this->data["edit"] = false;
            $this->data["platforms"] = $this->getPlatforms();
            $this->view = "Admin/adminProduct";
        }
        //edit product FORM
        else if ($params[0] == "edit" && isset($params[1]))
        {
            $game = $this->getGame($params[1]);
            if (!$game) $this->reroute("error");
            $this->data["edit"] = true;          
            $this->data["game"] = $game;
            $this->data["platforms"] = $this->getPlatforms();
            $this->view = "Admin/adminProduct";
        }  
        //order detail
        else if ($params[0] == "order" && isset($params[1]))
        {
            if ($this->getOrderDetail($params[1]))
            {
                $statuses = [
                    "Vyřízeno",
                    "Expedováno",
                    "Vyřizuje se",
                    "Storno",
                    "Reklamace"
                ];   
                $this->data["status"] = $statuses;
                $this->view = "Admin/adminOrder";
            }
            else $this->reroute("error");
        }
        else if ($params[0] == "changeStatus" && isset($_POST["idOrder"]))
        {
            $this->updateOrderStatus($_POST["idOrder"], $_POST["status"]);
            $this->data["action"] = "Status ob. ".$_POST["idOrder"]." změněn.";
        }
        /*else if ($params[0] == "remove" && isset($params[1]))
        {
            $game = $this->getGame($params[1]);
            if (!$game) $this->reroute("error");
            $this->removeGame($params[1]);
            $this->reroute("admin");
        }*/
    }
    
    private function getGame(string $url)
    {
        $pm = new ProductManager();
        return $pm->getGameByUrl($url);
    }
    private function getPlatforms()
    {
        $cm = new CategoryManager();
        return $cm->getAllPlatforms();
    }
    private function getOrderDetail(int $idOrder) : bool
    {
        $om = new OrderManager();
        $order = $om->getOrderFromIdAdmin($idOrder);
        if(!$order) return false;
        $this->data["order"] = $order;
        $this->data["products"] = $om->getOrderProducts($idOrder);
        return true;
    }
    private function updateOrderStatus(int $idOrder, string $status)
    {
        $om = new OrderManager();
        $om->updateStatus($idOrder, $status);
    }
    private function addProduct(array $input)
    {
        //have to comment this so that admin can enter tags like <br> etc. for description
        //(instead of doing it inside the database)
        /*foreach ($input as &$value)            
            $value = $this->testInput($value); 
        unset($value);*/
            
        $pm = new ProductManager();
        $idProduct = $pm->addProduct($input);
        
        $this->addImage($idProduct, $input["idPlatform"]);                       
    }
    private function updateProduct(int $idProduct,array $input)
    {
        /*foreach ($input as &$value)            
            $value = $this->testInput($value); 
        unset($value);*/
        
        $pm = new ProductManager();
        $pm->updateProduct($idProduct, $input);
        //if image was uploaded -> update image
        if(file_exists($_FILES["image"]["tmp_name"]) || is_uploaded_file($_FILES["image"]["tmp_name"])) 
            $this->addImage($idProduct, $input["idPlatform"]);
    }
    //adds or changes picture for product with set ID
    private function addImage(int $idProduct, int $idPlatform)
    {
        if(!file_exists($_FILES["image"]["tmp_name"]) && !is_uploaded_file($_FILES["image"]["tmp_name"])) return;
        $pm = new ProductManager();
        $cm = new CategoryManager();
        $platformDir = $cm->getPlatformTypeById($idPlatform);        
        $allowedTypes = [
          "image/jpeg",
          "image/png"
        ];
        $image = $_FILES["image"]; 
        if (!in_array($image["type"], $allowedTypes))
                die("Nepovolený typ souboru");
        
        $info = pathinfo($image["name"]);
        $imgName = $platformDir.DIRECTORY_SEPARATOR.$idProduct.".".$info["extension"];
        $path = dirname(__DIR__).DIRECTORY_SEPARATOR."imgs".DIRECTORY_SEPARATOR.$imgName;
              
        move_uploaded_file($image["tmp_name"], $path);        
        $pm->changePicture($idProduct, $imgName);
    }
    /*private function removeGame(string $url)
    {
        $pm = new ProductManager();
        $pm->removeGameByUrl($url);
    }*/
}