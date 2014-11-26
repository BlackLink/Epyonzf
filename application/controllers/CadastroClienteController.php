<?php

class CadastroClienteController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
           // action body
    }
    
    public function pfAction()
    {
        
    }
    
    public function pjAction()
    {
        
    }

    public function clienteAction()
    {
        $tipoCliente = $this->getParam('tipoCliente');
        
        if (!strcmp($tipoCliente, 'pf'))
            $this->redirect('/cadastro-cliente/pf');
        else
            $this->redirect('/cadastro-cliente/pj');
    }
    
    //public function inserirpf()
    //{
    //    $dados = $this->_getAllParams();
    //    $mdCliente = new Application_Model_Cliente();
    //    
    //    $mdCliente->insertPf($dados);
    //    
    //    var_dump($dados);die;
    //    
    //}

}

