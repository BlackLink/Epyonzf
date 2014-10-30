<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $modelPais = new Application_Model_Pais;
        
        $selectPaises = $modelPais->select();
        
        
        $this->view->assign('pais', $selectPaises);
    }


}

