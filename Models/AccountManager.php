<?php

class AccountManager
{
    public function getUser(string $username, string $pass)
    {
        $sql = "SELECT * FROM user WHERE username = :username AND password = sha2(:password, 256)";        
        $params = array(
            ":username" => $username,
            ":password" => $pass
        );
        return Database::query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }
    public function getUserById(int $idUser)
    {
        $sql = "SELECT * FROM user WHERE idUser = :idUser";
        $params = array(
            ":idUser" => $idUser
        );
        return Database::query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }
    public function findUserByUsername(string $username)
    {
        $sql = "SELECT idUser, username FROM user WHERE username = :username";
        $params = array(
            ":username" => $username
        );
        return Database::query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }
    public function findUserByEmail(string $email)
    {
        $sql = "SELECT idUser, username FROM user WHERE email = :email";
        $params = array(
          ":email" => $email  
        );
        return Database::query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }
    public function addUser(array $data)
    {
        $sql = "INSERT INTO user VALUES(DEFAULT, 'user', :username, sha2(:password, 256), :name, :surname, :address, :city, :psc, :phone, :email)";
        $params = array(            
            ":username" => $data["username"],
            ":password" => $data["password"],
            ":name" => $data["name"],
            ":surname" => $data["surname"],
            ":address" => $data["address"],
            ":city" => $data["city"],
            ":psc" => $data["psc"],
            ":phone" => $data["phone"],
            ":email" => $data["email"]
        );
        return Database::query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }
    public function updateUser(int $idUser, array $data)
    {
        $sql = "UPDATE user SET name = :name, surname = :surname, address = :address, "
                . "city = :city, psc = :psc, phone = :phone, email = :email "
                . "WHERE idUser = :idUser";
        $params = array(
            ":name" => $data["name"],
            ":surname" => $data["surname"],
            ":address" => $data["address"],
            ":city" => $data["city"],
            ":psc" => $data["psc"],
            ":phone" => $data["phone"],
            ":email" => $data["email"],
            ":idUser" => $idUser
        );
        Database::query($sql, $params);
    }
    public function changePassword(string $username, string $newPass)
    {
        $sql = "UPDATE user SET password = sha2(:password, 256) WHERE username = :username";
        $params = array(
            ":password" => $newPass,
            ":username" => $username
        );
        Database::query($sql, $params);
    }
    public function deleteUser(int $idUser)
    {
        $sql = "SELECT idCart FROM cart WHERE idUser = :idUser";
        $params = array(
            ":idUser" => $idUser
        );
        $idCart = Database::query($sql, $params)->fetchColumn();
                       
        //delete cartproducts corresponding to users cart
        Database::query("DELETE FROM cartproduct WHERE idCart = :idCart", array(":idCart" => $idCart));
        //delete users cart
        Database::query("DELETE FROM cart WHERE idCart = :idCart", array(":idCart" => $idCart));
        //delete user
        Database::query("DELETE FROM user WHERE idUser = :idUser", array(":idUser" => $idUser));
    }
}