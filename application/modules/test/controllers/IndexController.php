<?php
class Test_IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->view->apiUrl = "/projects/agrologistics/public/api/index/generate-graph";
    }    
    
}