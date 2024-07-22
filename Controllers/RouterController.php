<?php

class RouterController extends Controller
{
    protected $controller;
    
    public function process(array $params) : void
    {        
        $parsedUrl = $this->parseURL($params[0]);    
        if (empty($parsedUrl[0]) || $parsedUrl[0] == "index.php" || $parsedUrl[0] == "localhost")
            $this->reroute ("home");
        
        //get controller name and remove it from first parameter of parsedUrl
        $controllerClass = $this->getControllerName(array_shift($parsedUrl));                 
        //check if controller class exists and create instance, otherwise return 404
        if (file_exists("Controllers/" .$controllerClass. ".php"))
                $this->controller = new $controllerClass;
        else $this->reroute ("error");        
        //call main function in created controller
        $this->controller->process($parsedUrl);
        
        //if renderLayout is set to false => skip the rest 
        //(the layout will not be rendered)
        if (!$this->controller->renderLayout)return;
        
        if (isset($this->controller->header["title"]))                        
            $this->data["title"] = $this->controller->header["title"];
        else $this->data["title"] = "Herní e-shop";        
        $this->data["keywords"] = $this->controller->header["keywords"];
        $this->data["description"] = $this->controller->header["description"];
        
        //get cart amount and sum_price
        $cart = new Cart();
        $this->data["cartInfo"] = $cart->getCartInfo();
        //render layout
        $this->view = "layout";
    }
    
    //get the path from url and split it by '/' to get the parameters
    public function parseURL($url) : array
    {
        $parsedURL = parse_url($url);
        $parsedURL["path"] = ltrim($parsedURL["path"], "/");
        $parsedURL["path"] = trim($parsedURL["path"]);
        
        $pathParams = explode("/", $parsedURL["path"]);     
        return $pathParams;
    }
    
    //assemble controller class name
    private function getControllerName(string $str) : string
    {
        $name = str_replace('-', ' ', $str);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        return $name."Controller";
    }
    
}