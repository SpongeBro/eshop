<?php

class ErrorController extends Controller
{
    public function process(array $params) : void
    {
        header("HTTP/1.0 404 Not Found");
        $this->header["title"] = "404 Not Found";
        $this->view = "404";
    }
}
