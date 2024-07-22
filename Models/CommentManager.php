<?php

class CommentManager
{
    public function addComment(int $idProduct, int $idUser, string $text, string $user)
    {
        $sql = "INSERT INTO comments VALUES (DEFAULT, :idProduct, :idUser, :text, DEFAULT, :user)";
        $params = array(
            ":idProduct" => $idProduct,
            ":idUser" => $idUser,
            ":text" => $text,
            ":user" => $user
        );
        Database::query($sql, $params);
    }
    public function getAllProductComments($idProduct)
    {
        $sql = "SELECT * FROM comments WHERE idProduct = :idProduct";
        $params = array(
            ":idProduct" => $idProduct
        );
        return Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
}