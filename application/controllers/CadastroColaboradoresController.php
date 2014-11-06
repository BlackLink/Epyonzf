<?php

class CadastroColaboradoresController extends Zend_Controller_Action
{
    
    public function init()
    {
        /* Initialize action controller here */
        $this->modelEmailColaboradores = new Application_Model_EmailColaboradores();
        
    }

    public function indexAction()
    {
        $this->_helper->layout->setLayout('pos-login');
    }    
}

