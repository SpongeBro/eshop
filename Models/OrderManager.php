<?php

class OrderManager
{
    public function getAllOrders()
    {
        $orders = Database::query("SELECT * FROM orders")->fetchAll(PDO::FETCH_ASSOC);
        return $orders;
    }
    public function addOrder(int $idUser, string $address, string $transport, string $payment,  int $price_sum)
    {
        $sql = "INSERT INTO orders VALUES (DEFAULT, :idUser, :address, :transport, :payment, :price_sum, DEFAULT, 'Vyřizuje se')";
        $params = array(
            ":idUser" => $idUser,
            ":address" => $address,
            ":transport" => $transport,
            "payment" => $payment,
            ":price_sum" => $price_sum
        );
        Database::query($sql, $params);
        return Database::query("SELECT LAST_INSERT_ID()")->fetchColumn();
    }
    public function addOrderProduct(int $idProduct, int $idOrder, int $amount)
    {
        $sql = "INSERT INTO orderproduct VALUES (DEFAULT, :idProduct, :idOrder, :amount)";
        $params = array(
            ":idProduct" => $idProduct,
            ":idOrder" => $idOrder,
            ":amount" => $amount
        );
        Database::query($sql, $params);
    }
    public function getAllOrdersFromUserId(int $idUser)
    {
        $sql = "SELECT * FROM orders WHERE idUser = :idUser ORDER BY created_at DESC";
        $params = array(
            ":idUser" => $idUser
        );
        return Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getOrderFromUserId(int $idUser, int $idOrder)
    {
        $sql = "SELECT * FROM orders WHERE idUser = :idUser AND idOrder = :idOrder";
        $params = array(
            ":idUser" => $idUser,
            ":idOrder" => $idOrder
        );
        return Database::query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }
    public function getOrderProducts(int $idOrder)
    {
        $sql = "SELECT product.*, platform.platformType as 'platform', orderproduct.amount FROM orders "
                . "INNER JOIN orderproduct ON orders.idOrder = orderproduct.idOrder "
                . "INNER JOIN product ON product.idProduct = orderproduct.idProduct "
                . "INNER JOIN platform ON product.idPlatform = platform.idPlatform "
                . "WHERE orders.idOrder = :idOrder";
        $params = array(
            ":idOrder" => $idOrder
        );
        $products = Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as &$value)
        {
            $value["price_dph"] = Product::countDph($value["price"]) * $value["amount"];
            $value["price"] = $value["price"] * $value["amount"];
        }
        return $products;
    }
    //get all idProducts that has been ordered from specific user 
    //(to know which products user has ordered in the past)
    public function getAllOrderedProducts(int $idUser)
    {
        $sql = "SELECT DISTINCT orderproduct.idProduct from orders ".
               "INNER JOIN orderproduct ON orders.idOrder = orderproduct.idOrder ".
               "WHERE idUser = :idUser";
        $params = array(
            ":idUser" => $idUser
        );
        return Database::query($sql, $params)->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function getAllOrdersAdmin()
    {
        $sql = "SELECT idOrder, created_at, status FROM orders "
                . "ORDER BY created_at DESC";
        return Database::query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getOrderFromIdAdmin(int $idOrder)
    {
        $sql = "SELECT orders.*, user.name, user.surname, user.phone, user.email FROM orders "
                . "LEFT JOIN user ON user.idUser = orders.idUser "
                . "WHERE orders.idOrder = :idOrder";
        $params = array(
            ":idOrder" => $idOrder
        );
        return Database::query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }    
    public function searchByIdAdmin(string $idOrder)
    {
        $sql = "SELECT idOrder, created_at, status FROM orders "
                . "WHERE idOrder LIKE :idOrder";
        $params = array(
            ":idOrder" => $idOrder
        );
        return Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateStatus(int $idOrder, string $status)
    {
        $sql = "UPDATE orders SET status = :status WHERE idOrder = :idOrder";
        $params = array(
            ":idOrder" => $idOrder,
            ":status" => $status
        );
        Database::query($sql, $params);    
    }    
    
    /*
    public function getAllOrderProductsFromUserId(int $idUser)
    {
        $sql = "SELECT idOrder FROM orders WHERE idUser = :idUser";
        $params = array(
            "idUser" => $idUser
        );
        $orders = Database::query($sql, $params)->fetchAll(PDO::FETCH_COLUMN);
        $products = array();
        foreach ($orders as $value) 
        {
            array_push($products, $this->getOrderProducts($value));
        }
        return $products;
    }*/
   /* public function getOrderProducts(int $idOrder)
    {
        $sql = "SELECT product.*, orders.address, orders.price_sum, orders.created_at, orders.status, "
                . "orderproduct.amount FROM orders "
                . "INNER JOIN orderproduct ON orders.idOrder = orderproduct.idOrder "
                . "INNER JOIN product ON product.idProduct = orderproduct.idProduct "
                . "WHERE orders.idOrder = :idOrder";
        $params = array(
            ":idOrder" => $idOrder
        );
        $products = Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);                
        foreach ($products as &$value) 
        {
            $value["price_dph"] = Product::countDph($value["price"] * $value["amount"]);
            $value["price"] = $value["price"] * $value["amount"];
        }
        return $products;
    }*/
}