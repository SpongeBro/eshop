<?php

class ProductController extends Controller
{
    public function process(array $params): void 
    {        
        //add review
        if ($params[0] == "addComment" && $_SERVER["REQUEST_METHOD"] == "POST")
        {
            if (!isset($params[1]) || !isset($_SESSION["user"])) $this->reroute("error");
            if ($this->addComment($params[1], $_POST["text"]))
                    $this->reroute("Product/".$params[1]);
            else $this->reroute("error");
        }
        //show product
        else if (!empty($params))
        {
            $pm = new ProductManager;
            $cm = new CommentManager();
            $game = $pm->getGameByUrl($params[0]);
            if (!$game) $this->reroute ("error");
            else
            {
                $this->header["title"] = $game["name"];
                $this->data["game"] = $game;
                $this->view = "Product/index";
                //check if user has bought and not reviewed to display form for adding review
                if(isset($_SESSION["user"]))
                {                
                    $idUser = $_SESSION["user"]["idUser"];
                    $this->data["bought"] = $pm->hasUserBought($idUser, $game["idProduct"]);
                    $this->data["commented"] = $pm->hasUserCommented($idUser, $game["idProduct"]);
                }
                else                     
                    $this->data["bought"] = false;                  
                $this->data["comments"] = $cm->getAllProductComments($game["idProduct"]);
            }
        }
        else $this->reroute ("home");
    }
   
    
    private function addComment(string $productUrl, string $text) : bool
    {
        //check if game exists
        $pm = new ProductManager();
        $game = $pm->getGameByUrl($productUrl);
        if (!$game) return false;
        $idProduct = $game["idProduct"];
        $idUser = $_SESSION["user"]["idUser"];
        //check if user has bought the game in the past
        $bought = $pm->hasUserBought($idUser, $idProduct);
        if (!$bought) return false;
        //check if user has already written a review
        $commented = $pm->hasUserCommented($idUser, $idProduct);
        if ($commented) return false;
        
        //remove tags from input and add comment
        $cm = new CommentManager();
        $text = $this->testInput($text);        
        $user = $_SESSION["user"]["name"]." ".$_SESSION["user"]["surname"];
        $cm->addComment($idProduct, $idUser, $text, $user);
        return true;
    }
    

}