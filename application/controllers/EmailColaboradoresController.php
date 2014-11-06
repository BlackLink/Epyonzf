<?php

class EmailColaboradoresController extends Zend_Controller_Action
{
    private $nomeColaborador = null;
    private $emailColaborador = null;
    private $identColaborador = null;

    public function init()
    {
         /* Initialize action controller here */
        $this->modelEmailColaboradores = new Application_Model_EmailColaboradores();
        
    }

    public function indexAction()
    {
        $this->_helper->layout->setLayout('pos-login');
    }

    public function listarAction ()
    {
        $this->view->listColaboradores = $this->modelEmailColaboradores->selectColaboradores($this->identColaborador);
    }
}

