<?php

class Controller {
    public $model;
    public $view;
    private $request;

    function setRequest($request) {
        $this->request = $request;
    }

    function getRequest() {
        return $this->request;
    }
    
    function __construct() {
        $this->view = new View();
    }
    
    function action_index() {
        
    }
}
