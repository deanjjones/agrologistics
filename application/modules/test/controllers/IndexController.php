<?php
class Test_IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->view->test1 = "/projects/agrologistics/public/test/index/test-generate-graph";
        $this->view->test2 = "/projects/agrologistics/public/test/index/test-get-shipping-options-to-destination";
        $this->view->test3 = "/projects/agrologistics/public/test/index/test-get-products-available";
    }
    
    public function testGenerateGraphAction()
    {
        $this->view->apiUrl = "/projects/agrologistics/public/api/index/generate-graph";
    }
    
    public function testGetShippingOptionsToDestinationAction()
    {
        $this->view->apiUrl = "/projects/agrologistics/public/api/index/get-shipping-options-to-destination";
    }
    
    public function testGetProductsAvailableAction()
    {
        $this->view->apiUrl = "/projects/agrologistics/public/api/index/get-products-available";
    }
}