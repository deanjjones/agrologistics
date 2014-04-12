<?php

class CropWsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function updateAction()
    {
        // action body
        
        $form = new Application_Form_CropWs();
        $form->setAction(Zend_Controller_Front::getInstance()->getBaseUrl() . '/crop-ws/submit');
        $form->setMethod('post');
        $id = $this->_request->getParam('id');

        $data = $this->getArrayCrops($id);

        print_r($data);

        $form->populate($data['data'][0]);

        $this->view->form = $form;
    
    }

    public function listAllAction()
    {
        $data = $this->getArrayCrops();

        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data['data']));
        // show 10 bugs per page
        $paginator->setItemCountPerPage(5);
        // get the page number that is passed in the request.
        //if none is set then default to page 1.
        $page = $this->_request->getParam('page', 1);
        $paginator->setCurrentPageNumber($page);
        // pass the paginator to the view to render
        $this->view->paginator = $paginator;
    }

    public function submitAction()
    {
        $form = new Application_Form_CropWs();

        $postData = $this->getRequest()->getPost();
        
        $id = $this->getRequest()->getParam('id');


        if ($this->getRequest()->isPost())
        {
            if ($form->isValid($postData))
            {
                require_once (APPLICATION_PATH . '/../library/web_services_church/lib_nusoap/nusoap.php');

                date_default_timezone_set("America/Jamaica");


//Give it value at parameter 
                $arrParam = array("server" => "localhost",
                    "database" => "irieflex_agrolog",
                    "userID" => "irieflex_agrolog",
                    "password" => "876lano123",
                    "dbtype" => "mysql",
//    "query" => "update crop set name = 'rixe' where id = 1");
                    "query" => "update crop"
                    . "            set name = '{$_POST['name']}' ,"
                    . "                shelf_life_air = '{$_POST['shelf_life_air']}' ,"
                    . "                shelf_life_freezer = '{$_POST['shelf_life_freezer']}' ,"
                    . "                shelf_life_fridge = '{$_POST['shelf_life_fridge']}' ,"
                    . "                date_available = '{$_POST['date_available']}' ,"
                    . "                expected_quantity = '{$_POST['expected_quantity']}' "
                    . "  where id =  {$id} ");

                $param = array("dbInfo" => json_encode($arrParam));
//Create object that referer a web services 
                $client = new nusoap_client('http://irieflex.com/web_services_church/nu_soap_server.php');

//echo "hello - PPP";
//Call a function at server and send parameters too 
                $response = $client->call('get_dbinfo', $param);
//Process result 
                if ($client->fault)
                {
                    echo "FAULT: <p>Code: (" . $client->faultcode . "</p>";
                    echo "String: " . $client->faultstring;
                }
                else
                {
                    echo "record updated " . print_r(json_decode($response, true), true);
//                    exit();
//                    return json_decode($response, true);
                }
                
                
        $this->_redirect('/crop-ws/list-all');
            }

            
//            echo "not here";
        }
        else
        {
        $this->redirect('/crop-ws/update/id');
        }

//        echo "yah nice";

        $form->populate($postData);
        $this->view->form = $form;      


    }

    
    
    private function getArrayCrops($id = null)
    {
//        $jsonFile = APPLICATION_PATH . "/../_resources/crop-delano.json";
//        return json_decode(file_get_contents($jsonFile), true);


        require_once (APPLICATION_PATH . '/../library/web_services_church/lib_nusoap/nusoap.php');

        date_default_timezone_set("America/Jamaica");

        if ($id != null)
        {
            $id = " where id = {$id}";
        }

//Give it value at parameter 
        $arrParam = array("server" => "localhost",
            "database" => "irieflex_agrolog",
            "userID" => "irieflex_agrolog",
            "password" => "876lano123",
            "dbtype" => "mysql",
//    "query" => "update crop set name = 'rixe' where id = 1");
            "query" => "select * from crop $id ");

        $param = array("dbInfo" => json_encode($arrParam));
//Create object that referer a web services 
        $client = new nusoap_client('http://irieflex.com/web_services_church/nu_soap_server.php');

//echo "hello - PPP";
//Call a function at server and send parameters too 
        $response = $client->call('get_dbinfo', $param);
//Process result 
        if ($client->fault)
        {
            echo "FAULT: <p>Code: (" . $client->faultcode . "</p>";
            echo "String: " . $client->faultstring;
        }
        else
        {
            return json_decode($response, true);
        }
    }
    

}







