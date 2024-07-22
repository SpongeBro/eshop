<?php

class CategoryManager
{
    public function getAll()
    {
        $sql = "SELECT * FROM category ORDER BY genre";
        return Database::query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCatForPlatform(string $platform)
    {
        $sql = "SELECT * FROM category "
               ."INNER JOIN platform ON platform.idPlatform = category.idPlatform "
               ."WHERE platform.platformType = :platform";
        $params = array(
            ":platform" => $platform
        );
        $cat = Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
        if (!$cat) return null;
        else return $cat;
    }
    public function getCatsBasedOnGenre(string $genreUrl)
    {
        $idPlatform = Database::query("SELECT idPlatform FROM category "
                . "WHERE url = :genreUrl", array(":genreUrl" => $genreUrl))->fetchColumn();
        $sql = "SELECT * FROM category "
                . "WHERE idPlatform = :idPlatform";
        $params = array(
            ":idPlatform" => $idPlatform
        );
        $cat = Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
        if (!$cat) return null;
        else return $cat;
    }
    //get categories by platformId
    public function getByPlatformId(int $idPlatform)
    {
        $sql = "SELECT * FROM category WHERE idPlatform = :idPlatform ORDER BY genre";
        $params = array(
            ":idPlatform" => $idPlatform
        );
        return Database::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllPlatforms()
    {
        return Database::query("SELECT * FROM platform")->fetchAll(PDO::FETCH_ASSOC);
    }
    //get platformType by platformId
    public function getPlatformTypeById(int $idPlatform)
    {
        $sql = "SELECT platformType from platform WHERE idPlatform = :idPlatform";
        $params = array(
            ":idPlatform" => $idPlatform
        );
        return Database::query($sql, $params)->fetchColumn();
    }
}

