<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
     public function __construct($application)
    {
        parent::__construct($application);
    }
    
    protected function _initDoctype()
    {
        $this->bootstrap('view');

        $view = $this->getResource('view');

        $view->doctype('XHTML1_STRICT');
    }

}

