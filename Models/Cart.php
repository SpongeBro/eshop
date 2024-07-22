<?php

class Cart
{
    private $idCart;
    
    public function __construct() 
    {
        $this->idCart = $this->createCart();           
    }
    
    //create cart and return its id (or return existing cart id)
    private function createCart() : int
    {
        $cm = new CartManager();
        //if user is not logged in and doesnt have any cart registered in session
        //=> create new cart and store its id into session
        //or return cart id from existing session
        if (!isset($_SESSION["user"]))
        {
            if (!isset($_SESSION["idCart"]))
            {
                $idCart = $cm->addCart(0);
                $_SESSION["idCart"] = $idCart;
                return $idCart;
            }
            else return $_SESSION["idCart"];              
        }
        //if user is logged in (redundant)
        if (isset($_SESSION["user"]))
        {
            $idUser = $_SESSION["user"]["idUser"];
            //check if cart is already registered to logged in user   
            $cart = $cm->getCartByUserId($idUser);
                        
            if ($cart) return $cart["idCart"];
            
            //cart has been created by offline user
            //=> link to logged in user
            else if (isset($_SESSION["idCart"]))
            {
                $idCart = $_SESSION["idCart"];
                $cm->updateCartUserId($idCart, $idUser);
                unset($_SESSION["idCart"]);
                return $idCart;
            }
            //cart is not registered and has not been created yet (redundant, user will get cart from session)  
            else
            {
                $idCart = $cm->addCart($idUser);
                return $idCart;
            }
        }
        else return null;
    } 
    
    public function getCart()
    {
        $cm = new CartManager();
        $cart = $cm->getCartById($this->idCart);
        return $cart;
    }
    
    public function getCartProducts()
    {
        $cm = new CartManager();
        $products = $cm->getAllCartProducts($this->idCart);
        return $products;
    }
    
    public function getCartInfo()
    {
        $cm = new CartManager();
        $info = $cm->getCartInfoById($this->idCart);
        return $info;
    }
    
    public function addToCart(string $productUrl) : bool
    {
        $cm = new CartManager();
        $pm = new ProductManager();
        $product = $pm->getGameByUrl($productUrl);
        if ($product && $product["stock"] > 0)
        {
            $idProduct = $product["idProduct"];
            $cartProduct = $cm->getCartProduct($this->idCart, $idProduct);
            //if product is not in cart yet, add it, otherwise increase its amount by one
            if (!$cartProduct)
                $cm->addCartProduct($this->idCart, $idProduct);            
            else
                $cm->updateCartProduct ($this->idCart, $idProduct, true);
            
            $cm->updateCart($this->idCart, 1, $product["price_dph"]);
            return true;
        }
        return false;
    }
    
    public function removeFromCart(string $productUrl) : bool
    {
        $cm = new CartManager();
        $pm = new ProductManager();
        $product = $pm->getGameByUrl($productUrl);
        if ($product)
        {
            $idProduct = $product["idProduct"];
            $cartProduct = $cm->getCartProduct($this->idCart, $idProduct);
            if (!$cartProduct) return false;
            
            if ($cartProduct["amount"] > 1)
                $cm->updateCartProduct ($this->idCart, $idProduct, false);
            else
                $cm->removeCartProduct($this->idCart, $idProduct);
                
            $cm->updateCart($this->idCart, -1, -$product["price_dph"]);
            return true;
        }
        return false;
    }   
    
    public function order(string $transport, string $payment) : bool
    {
        $cm = new CartManager();
        $pm = new ProductManager();
        $om = new OrderManager();
        $cart = $cm->getCartById($this->idCart);
        $cartProducts = $cm->getAllCartProducts($this->idCart);
        //user needs to have products in cart in order to proceed
        if (!$cartProducts) return false;                       
        
        //check if products are in stock
        foreach ($cartProducts as $value) 
        {
            if ($value["stock"] - $value["amount"] < 0) 
                return false;
        }   
        
        $address = "Ulice: ".$_SESSION["user"]["address"].
                ", Město: ".$_SESSION["user"]["city"].
                ", PSČ: ".$_SESSION["user"]["psc"];
        $idUser = $_SESSION["user"]["idUser"];
        
        $transportPrice = 0;
        if ($transport == "Česká pošta") $transportPrice = Payment::$post;
        else if ($transport == "Kurýr") $transportPrice = Payment::$courier;
        $price_sum = $cart["price_sum"] + $transportPrice;
        
        //create an order and reset cart
        $idOrder = $om->addOrder($idUser, $address, $transport, $payment, $price_sum); 
        $cm->updateCart($this->idCart, -$cart["amount_sum"], -$cart["price_sum"]);
        
        //decrease stock value from each product, add to order product table and remove all cart products
        foreach ($cartProducts as $value) 
        {
            $pm->updateProductStock($value["idProduct"], $value["amount"], false);
            $om->addOrderProduct($value["idProduct"], $idOrder, $value["amount"]);
        }
        $cm->removeAllCartProducts($this->idCart);
        
        return true;
    }
}