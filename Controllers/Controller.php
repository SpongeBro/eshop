<?php

abstract class Controller
{
    protected $data = array(); //data passed to View
    protected $view = ""; //which View to render
    protected $header = array("title" => "", "keywords" => "", "description" => "");
    protected $renderLayout = true;
    
    abstract function process(array $params) : void;
    
    public function render() : void
    {
        //render view
        if ($this->view)
        {
            extract($this->forceTags($this->data));
            extract($this->data, EXTR_PREFIX_ALL, "");
            require ("Views/" .$this->view. ".phtml");
        }
    }
    
    public function reroute($url) : never
    {
        header("Location: /$url");
        header("Connection: close");
        exit();
    }
    
    private function forceTags($str = null)
    {
        if (!isset($str)) return null;
        else if (is_string($str)) return htmlspecialchars ($str, ENT_QUOTES);
        else if (is_array($str))
        {
            foreach ($str as $key => $val)
                $str[$key] = $this->forceTags ($val);
            return $str;
        }
        else return $str;
    }
    
    protected function testInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}