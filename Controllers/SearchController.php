<?php

class SearchController extends Controller
{
    
    public function process(array $params): void 
    {
        //only for admin
        if (!isset($_SESSION["user"]) && $_SESSION["user"]["role"] != "admin") return;
        
        //disable render
        $this->renderLayout = false;
        if (empty($params)) return;
        else if ($params[0] == "pAdmin" && isset($_GET["key"]))
            $this->findProductsAdmin($_GET["key"]);
        else if ($params[0] == "oAdmin" && isset($_GET["key"]))
            $this->findOrdersAdmin($_GET["key"]);
        
    }
    
    private function findProductsAdmin(string $key)
    {
        $pm = new ProductManager();
    
        //show all games without redundant info
        if ($key == "")
            $this->showProductsAdmin($pm->getAllGamesAdmin());
    
        //show games without redundant info by value 'key'
        else
            $this->showProductsAdmin($pm->searchByNameAdmin($key));
    }
    
    private function showProductsAdmin($products)
    {
        echo "<h2>Upravit produkt:</h2>";
        echo "<div>";
        foreach($products as $value)
           echo '<div style="padding-bottom:5px;"><a href="admin/edit/'.$value["url"].'">'.$value["name"].'</a> ('.$value["platform"].')</div>';
        echo "</div>";
    }
    
    private function findOrdersAdmin(string $key)
    {
        $om = new OrderManager();
        
        if ($key == "")
            $this->showOrdersAdmin($om->getAllOrdersAdmin());
        
        else
            $this->showOrdersAdmin($om->searchByIdAdmin($key));        
    }
    
    private function showOrdersAdmin($orders)
    {
        echo "<h2>Zobrazit objednávku:</h2>";        
        echo "<div>";
        echo "<table style='margin-left:auto;margin-right:auto;'>";
        echo "<tr><th>ID</th><th>Datum</th><th>Status</th></tr>";
        foreach($orders as $value)            
            echo '<tr><td><a href="admin/order/'.$value["idOrder"].'">'.$value["idOrder"].'</a></td><td>'.$value["created_at"].'</td><td>'.$value["status"].'</td></tr>';
        echo "</table>";
        echo "</div>";
    }
}

