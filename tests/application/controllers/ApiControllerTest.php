<?php

class IndexControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    public function testGenerateGraphAction()
    {
          //generate url
        $params                 = array('action' => 'generate-graph', 'controller' => 'index', 'module' => 'api');
        $urlParams              = $this->urlizeOptions($params);
        $url                    = $this->url($urlParams);
        
        //set data
        $data['title']     = "Chart Title";
        $data['data']      = array(13, 232, 324, 34, 34);
        
        $postParams['requestData'] = json_encode($data);
                
        $this->request
            ->setMethod('POST')
            ->setPost($postParams);
        
        $this->dispatch($url, 'POST', $postParams);
        
        $bodyText = $this->getResponse()->getBody();
        
        // assertions
        $this->assertModule($params['module']);
        $this->assertController($params['controller']);
        $this->assertAction($params['action']);
        
        $positionOfHeader = strpos($bodyText, "‰PNG");
        
        $this->assertEquals($positionOfHeader, 0, $message = 'The response is not an encoded PNG: ' . $positionOfHeader);
        
    }
}