<?php

class CartManager
{
    public function addCart(int $idUser)
    {
        $sql = "INSERT INTO cart VALUES (DEFAULT, :idUser, 0, 0)";
        $params = array(
          ":idUser" => $idUser
        );
        Database::query($sql, $params);
        return Database::query("SELECT LAST_INSERT_ID()")->fetchColumn();
    }
    
    public function getCartById(int $idCart)
    {
        $sql = "SELECT * FROM cart WHERE idCart = :idCart";
        $params = array(
            ":idCart" => $idCart
        );
        return Database::query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getCartInfoById(int $idCart)
    {
        $sql = "SELECT amount_sum, price_sum FROM cart WHERE idCart = :idCart";
        $params = array(
            ":idCart" => $idCart
        );
        return Database::query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getCartByUserId(int $idUser)
    {
        $sql = "SELECT * FROM cart WHERE idUser = :idUser";
        $params = array(
          ":idUser" => $idUser  
        );
        return Database::query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateCart(int $idCart, int $newAmount, int $newPriceSum)
    {
        $cart = $this->getCartById($idCart);
        $amount = $cart["amount_sum"] + $newAmount;
        $priceSum = $cart["price_sum"] + $newPriceSum;
        
        $sql = "UPDATE cart SET amount_sum = :amount_sum, price_sum = :price_sum WHERE idCart = :idCart";
        $params = array(
          ":amount_sum" => $amount,
          ":price_sum" => $priceSum,
          ":idCart" => $idCart
        );
        Database::query($sql, $params);
    }
    
    public function updateCartUserId(int $idCart, int $idUser)
    {
        $sql = "UPDATE cart SET idUser = :idUser WHERE idCart = :idCart";
        $params = array(
          ":idUser" => $idUser,
          ":idCart" => $idCart
        );
        Database::query($sql, $params);
    }
    
    public function removeCart(int $idCart)
    {
        $sql = "DELETE FROM cart WHERE idCart = :idCart";
        $params = array(
            ":idCart" => $idCart
        );
        Database::query($sql, $params);
    }

    public function addCartProduct(int $idCart, int $idProduct)
    {
        $sql = "INSERT INTO cartproduct VALUES (DEFAULT, :idProduct, :idCart, 1)";
        $params = array(
          ":idProduct" => $idProduct,
          ":idCart" => $idCart
        );
        Database::query($sql, $params);
    }
    
    public function updateCartProduct(int $idCart, int $idProduct, bool $increase)
    {
        $sql = "SELECT amount FROM cartproduct WHERE idProduct = :idProduct AND idCart = :idCart";
        $params = array(
            ":idProduct" => $idProduct,
            ":idCart" => $idCart
        );
        $amount = Database::query($sql, $params)->fetchColumn();
        if ($increase) $amount++;
        else $amount--;
        $sql = "UPDATE cartproduct SET amount = :amount WHERE idProduct = :idProduct AND idCart = :idCart";
        $params = array(
            ":amount" => $amount,
            ":idProduct" => $idProduct,
            "idCart" => $idCart
        );
        Database::query($sql, $params);
    }
        
    public function getAllCartProducts(int $idCart)
    {
        $sql = "SELECT product.*, platform.platformType as 'platform', amount FROM cartproduct "
                . "INNER JOIN product ON cartproduct.idProduct = product.idProduct "
                . "INNER JOIN platform ON product.idPlatform = platform.idPlatform "
                . "WHERE idCart = :idCart";
        $params = array(
          ":idCart" => $idCart  
        );
        $games = Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);             
        foreach ($games as &$value) 
            $value["price_dph"] = Product::countDph($value["price"]);
        unset($value);        
        return $games;
    }
    
    public function getAllProductStock(int $idCart)
    {
        $sql = "SELECT product.idProduct, stock FROM product INNER JOIN cartproduct ON product.idProduct = cartproduct.idProduct "
                . "WHERE idCart = :idCart";
        $params = array(
            ":idCart" => $idCart
        );
        return Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCartProduct(int $idCart, int $idProduct)
    {
        $sql = "SELECT * FROM cartproduct WHERE idProduct = :idProduct AND idCart = :idCart";
        $params = array(
          ":idProduct" => $idProduct,
          ":idCart" => $idCart
        );
        return Database::query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }
    
    public function removeAllCartProducts(int $idCart)
    {
        $sql = "DELETE FROM cartproduct WHERE idCart = :idCart";
        $params = array(
            ":idCart" => $idCart
        );
        Database::query($sql, $params);
    }
    
    public function removeCartProduct(int $idCart, int $idProduct)
    {
        $sql = "DELETE FROM cartproduct WHERE idCart = :idCart AND idProduct = :idProduct LIMIT 1";
        $params = array(
          ":idCart" => $idCart,
          ":idProduct" => $idProduct
        );
        Database::query($sql, $params);
    }    
}