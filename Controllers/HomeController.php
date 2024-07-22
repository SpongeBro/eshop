<?php

class HomeController extends Controller
{
    public function process(array $params): void 
    {
        $this->header["title"] = "Planet of Games";        
        $pm = new ProductManager();
        $cm = new CategoryManager();
        //display main page
        if (empty($params))
        {
            $this->data["newGames"] = $pm->getNewestGames();
            $this->data["games"] = $pm->getRandomGames();            
            $this->view = "Home/index";
        }
        else if ($params[0] == "find" && $_SERVER["REQUEST_METHOD"] == "POST")
        {
            $this->data["games"] = $this->find($_POST["search_word"]);
            $this->view = "Home/catalogue";
        }  
        //display games for platform
        else if (in_array($params[0], array("PC", "XBOX", "PLAYSTATION", "SWITCH")))
        {
            //check if max price is specified
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["priceMax"]))
            {
                $this->data["games"] = $pm->getGamesByPlatformPrice ($params[0], $_POST["priceMax"]);
                $this->data["price"] = $_POST["priceMax"];
            }
            else $this->data["games"] = $pm->getGamesByPlatform($params[0]);
            $this->data["categories"] = $cm->getCatForPlatform($params[0]);
            $this->data["p"] = $params[0]; //save platform type (for max price form)
            $this->view = "Home/catalogue";          
        }
        //display games for genre
        else 
        {         
            //check if max price is specified
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["priceMax"]))
            {                
                $this->data["games"] = $pm->getGamesByGenreAndPrice($params[0], $_POST["priceMax"]);            
                $this->data["price"] = $_POST["priceMax"];
            }
            else $this->data["games"] = $pm->getGamesByGenre($params[0]);
            $this->data["categories"] = $cm->getCatsBasedOnGenre($params[0]);
            $this->data["g"] = $params[0]; //save genre (for max price form)
            $this->view = "Home/catalogue";
        }
    }
   /* private function getAllGames()
    {
        $pm = new ProductManager();
        return $pm->getAllGames();
    }
    private function getAllCat()
    {
        $cm = new CategoryManager();
        return $cm->getAll();
    } */
    private function find(string $key)
    {
        $key = $this->testInput($key);
        $pm = new ProductManager();        
        return $pm->searchByName($key);        
    }
}