<?php
//ALTER TABLE tablename AUTO_INCREMENT = 1

class CartController extends Controller
{
    private $cart;
    
    public function __construct() 
    {
        $this->cart = new Cart();
        $this->data["cart"] = $this->cart->getCart();
        $this->data["products"] = $this->cart->getCartProducts();
    }

    public function process(array $params): void 
    {                      
        if (empty($params)) 
        {     
            if (isset($_GET["sum"]) && isset($_SESSION["user"])) 
            {
                $this->header["title"] = "Shrnutí objednávky";          
                $this->view = "Cart/index2"; //summary of order
            }            
            else 
            {
                $this->header["title"] = "Košík";             
                $this->view="Cart/index";  //cart                                      
            }
        }
        //add product to cart
        else if ($params[0] == "add" && isset($params[1]))
        {
            if ($this->cart->addToCart($params[1]))   
            {
                $page = "product/".$params[1];
                if (isset($_GET["p"]))                    
                    $page = $_GET["p"] == "" ? "home" : "home/".$_GET["p"];                              
                $this->reroute($page);
            }
                //$this->reroute ("product/".$params[1]);
            else $this->reroute ("home");
        }  
        //remove product from cart
        else if ($params[0] == "remove" && isset($params[1]))
        {
            if ($this->cart->removeFromCart($params[1]))
                    $this->reroute ("cart");
            else $this->reroute ("home");
        }
        //make an order from cart
        else if ($params[0] == "order" && $_SERVER["REQUEST_METHOD"] == "POST")
        {
            if(!isset($_SESSION["user"]))
                $this->reroute ("account/login");
            
            else if ($this->cart->order($_POST["transport"], $_POST["payment"]))
                $this->view = "Cart/success";
        }
        else $this->reroute ("home");
    }
}