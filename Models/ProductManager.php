<?php

class ProductManager
{
    public function getAllGames()
    {
        $games = Database::query("SELECT product.*, platform.platformType as 'platform' FROM product "
                . "INNER JOIN platform ON platform.idPlatform = product.idPlatform")->fetchAll(PDO::FETCH_ASSOC);
        return $this->countDphGames($games);
    }                 
    public function getGameByUrl(string $url)
    {
        $sql = "SELECT product.*, platform.platformType as 'platform' FROM product "
                . "INNER JOIN platform ON platform.idPlatform = product.idPlatform "
                . "WHERE product.url = :url";
        $params = array(
            ":url" => $url
        );
        $game = Database::query($sql, $params)->fetch(PDO::FETCH_ASSOC);
        return $this->countDphGame($game);
    }
    public function getGameById(int $idProduct)
    {
        $sql = "SELECT product.*, platform.platformType as 'platform' FROM product "
                . "INNER JOIN platform ON platform.idPlatform = product.idPlatform "
                . "WHERE product.idProduct = :idProduct";
        $params = array(
          ":idProduct" => $idProduct  
        );
        $game = Database::query($sql, $params)->fetch(PDO::FETCH_ASSOC);
        return $this->countDphGame($game);
    }
    //get games for specific platform
    public function getGamesByPlatform(string $platform)
    {
        $sql = "SELECT product.*, platform.platformType as 'platform' FROM product "
                . "INNER JOIN platform ON platform.idPlatform = product.idPlatform "
                . "WHERE platform.platformType = :platform";
        $params = array(
            ":platform" => $platform
        );
        $games = Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
        return $this->countDphGames($games);
    }   
    //get games for specific platform AND for less than specific price
    public function getGamesByPlatformPrice(string $platform, int $price)
    {
        $sql = "SELECT product.*, platform.platformType as 'platform' FROM product "
               ."INNER JOIN platform ON platform.idPlatform = product.idPlatform "
               ."WHERE price * :dph < :price AND platform.platformType = :platform";
        $params = array(
            ":dph" => Product::getDph(),
            ":price" => $price,
            ":platform" => $platform
        );
        $games = Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
        return $this->countDphGames($games);
    }
    //get games for specific genre (and platform)
    public function getGamesByGenre(string $genreUrl)
    {
        //get genre info (id platform, type of genre) from genre URL
        $genre = Database::query("SELECT idPlatform, genre FROM category "
                . "WHERE url = :url", array(":url" => $genreUrl))->fetch(PDO::FETCH_ASSOC);
        if (!$genre) return null;
        
        $sql = "SELECT product.*, platform.platformType as 'platform' FROM product "
               ."INNER JOIN platform ON platform.idPlatform = product.idPlatform "
               ."WHERE genre LIKE CONCAT ('%', :genre, '%') AND product.idPlatform = :idPlatform";        
        $params = array(
            ":genre" => $genre["genre"],
            ":idPlatform" => $genre["idPlatform"]
        );
        $games = Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
        return $this->countDphGames($games);
    }
    //get games for specific genre AND for less than specific price
    public function getGamesByGenreAndPrice(string $genreUrl, int $price)
    {
        $genre = Database::query("SELECT idPlatform, genre FROM category "
                . "WHERE url = :url", array(":url" => $genreUrl))->fetch(PDO::FETCH_ASSOC);
        if (!$genre) return null;
        
        $sql = "SELECT product.*, platform.platformType as 'platform' FROM product "
               ."INNER JOIN platform ON platform.idPlatform = product.idPlatform "
               ."WHERE genre LIKE CONCAT ('%', :genre, '%') "
                . "AND product.idPlatform = :idPlatform "
                . "AND price * :dph < :price";        
        $params = array(
            ":dph" => Product::getDph(),
            ":genre" => $genre["genre"],
            ":idPlatform" => $genre["idPlatform"],
            ":price" => $price
        );
        $games = Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
        return $this->countDphGames($games);
    }
    //get 8 random games
    public function getRandomGames()
    {
        $sql = "SELECT product.*, platform.platformType as 'platform' FROM product "
              ."INNER JOIN platform ON platform.idPlatform = product.idPlatform "
              . "WHERE stock > 0 "
              ."ORDER BY RAND() LIMIT 8";
        $games = Database::query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $this->countDphGames($games);
    }
    //get 5 latest added games
    public function getNewestGames()
    {
        $sql = "SELECT product.*, platform.platformType as 'platform' FROM product "
               ."INNER JOIN platform ON platform.idPlatform = product.idPlatform "
               . "WHERE stock > 0 "
               ."ORDER BY idProduct DESC LIMIT 5";
        $games = Database::query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $this->countDphGames($games);
    }
    public function updateProductStock(int $idProduct, int $amount, bool $increase)
    {
        $sql = "SELECT stock FROM product WHERE idProduct = :idProduct";
        $params = array(
            ":idProduct" => $idProduct
        );
        $stock = Database::query($sql, $params)->fetchColumn();
        if ($increase) $stock += $amount;
        else $stock -= $amount; 
        $sql = "UPDATE product SET stock = :stock WHERE idProduct = :idProduct";
        $params = array(
            ":stock" => $stock,
            ":idProduct" => $idProduct
        );
        Database::query($sql, $params);
    }
    //return true if user has ordered certain product in the past 
    //otherwise return false
    public function hasUserBought(int $idUser, int $idProduct) : bool
    {
        $sql = "SELECT DISTINCT orderproduct.idProduct from orders ".
               "INNER JOIN orderproduct ON orders.idOrder = orderproduct.idOrder ".
               "WHERE orderproduct.idProduct = :idProduct AND orders.idUser = :idUser";
        $params = array(
            ":idProduct" => $idProduct,
            ":idUser" => $idUser
        );
        $product = Database::query($sql, $params)->fetchColumn();
        if ($product) return true;
        else return false;
    }
    public function hasUserCommented(int $idUser, int $idProduct) : bool
    {
        $sql = "SELECT idComment FROM comments "
                . "WHERE idProduct = :idProduct AND idUser = :idUser";
        $params = array(
            ":idProduct" => $idProduct,
            ":idUser" => $idUser
        );
        $comment = Database::query($sql, $params)->fetchColumn();
        if($comment) return true;
        else return false;
    }
    public function searchByName(string $name)
    {
        $sql = "SELECT product.*, platform.platformType as 'platform' FROM product "
                . "INNER JOIN platform ON platform.idPlatform = product.idPlatform "
                . "WHERE product.name LIKE CONCAT ('%', :name, '%')";
        $params = array(
            ":name" => $name
        );
        $games = Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
        return $this->countDphGames($games);
    }
    //get non redundant info about game
    public function getAllGamesAdmin()
    {
        $sql = "SELECT product.url, product.name, platform.platformType as 'platform' FROM product "
                . "INNER JOIN platform ON platform.idPlatform = product.idPlatform";
        return Database::query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchByNameAdmin(string $name)
    {
        $sql = "SELECT product.url, product.name, platform.platformType as 'platform' FROM product "
                . "INNER JOIN platform ON platform.idPlatform = product.idPlatform "
                . "WHERE product.name LIKE CONCAT ('%', :name, '%')";
        $params = array(
            ":name" => $name
        );
        return Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function addProduct(array $data)
    {
        $sql = "INSERT INTO product VALUES (DEFAULT, :idPlatform, :url, :name, :genre, :game_type, :price, :stock, :description, '')";
        $params = array(
            ":idPlatform" => $data["idPlatform"],
            ":url" => $data["url"],
            ":name" => $data["name"],
            ":genre" => $data["genre"],
            ":game_type" => $data["game_type"],
            ":price" => $data["price"],
            ":stock" => $data["stock"],
            ":description" => $data["description"]
        );
        Database::query($sql, $params);
        return Database::query("SELECT LAST_INSERT_ID()")->fetchColumn();
    }
    public function updateProduct(int $idProduct, array $data)
    {        
        $this->updateCartProductPrice($idProduct, $data["price"]);
        $sql = "UPDATE product SET idPlatform = :idPlatform, url = :url, name = :name, genre = :genre, "
                . "game_type = :game_type, price = :price, stock = :stock, description = :description "
                . "WHERE idProduct = :idProduct";
        $params = array(
            ":idProduct" => $idProduct,
            ":idPlatform" => $data["idPlatform"],
            ":url" => $data["url"],
            ":name" => $data["name"],
            ":genre" => $data["genre"],
            ":game_type" => $data["game_type"],
            ":price" => $data["price"],
            ":stock" => $data["stock"],
            ":description" => $data["description"]
        );
        Database::query($sql, $params);        
    }
    public function changePicture(int $idProduct, string $image)
    {
        $sql = "UPDATE product SET image = :image WHERE idProduct = :idProduct";
        $params = array(
            ":idProduct" => $idProduct,
            ":image" => $image
        );
        Database::query($sql, $params);
    }
    //updates price of a product in every cart associated with it
    //(when updating price as ADMIN => need to update every cart that had the old price with the new price
    private function updateCartProductPrice(int $idProduct, int $newPrice)
    {
        //get old price from product
        $sql = "SELECT price FROM product WHERE idProduct = :idProduct";
        $oldPrice = Database::query($sql, array(":idProduct" => $idProduct))->fetchColumn();
        //if price has not been changed => skip the rest
        if ($oldPrice == $newPrice) return;
        $oldPrice = Product::countDph($oldPrice);
        $newPrice = Product::countDph($newPrice);
        
        $sql = "SELECT cart.idCart, price_sum, cartproduct.amount FROM cart "                
                ."INNER JOIN cartproduct ON cartproduct.idCart = cart.idCart "
                ."INNER JOIN product ON product.idProduct = cartproduct.idProduct "
                ."WHERE product.idProduct = :idProduct";
        $params = array(
            ":idProduct" => $idProduct
        );
        
        $carts = Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($carts as $value)
        {
            $minusPrice = $oldPrice * $value["amount"];
            $this->updateCartPrice($value["idCart"], $minusPrice, false);
            $plusPrice = $newPrice * $value["amount"];
            $this->updateCartPrice($value["idCart"], $plusPrice, true);
        }
    }
    private function updateCartPrice(int $idCart, int $price, bool $increase)
    {
        $sql = "SELECT price_sum FROM cart WHERE idCart = :idCart";
        $price_sum = Database::query($sql, array(":idCart" => $idCart))->fetchColumn();
        if ($increase) $price_sum += $price;
        else $price_sum -= $price;
        $sql = "UPDATE cart SET price_sum = :price_sum WHERE idCart = :idCart";
        $params = array(
            ":price_sum" => $price_sum,
            ":idCart" => $idCart
        );
        Database::query($sql, $params);
    }
    private function countDphGames(array $games)
    {
        if (!$games) return null;
        
        foreach ($games as &$value)         
            $value["price_dph"] = Product::countDph($value["price"]);
        unset($value);
        return $games;
    }
    private function countDphGame($game)
    {
        if (!$game) return null;
        
        $game["price_dph"] = Product::countDph($game["price"]);
        return $game;
    }
   /* public function removeGameByUrl(string $url)
    {
        $sql = "DELETE FROM product WHERE url = :url";
        $params = array(
            ":url" => $url
        );
        Database::query($sql, $params);
    }*/    
}