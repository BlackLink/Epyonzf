<?php

class EmailColaboradoresController extends Zend_Controller_Action
{
    private $nomeColaborador = null;
    private $emailColaborador = null;
    private $identColaborador = null;
    private $idUser = null;
    private $userName = null;
    var $usuario;
    
    public function init()
    {
         /* Initialize action controller here */
        
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/index');
        }

        $auth = Zend_Auth::getInstance();
        
        if ($auth->hasIdentity()) {
            $identity = $auth->getIdentity();
            $this->usuario = get_object_vars($identity);
        }

        $this->_helper->layout->setLayout('pos-login');
        
    }

    public function indexAction()
    {
        $dadosColaborador = new Application_Model_EmailColaboradores();
        $dados = $dadosColaborador->selectColaborador($where=null, $order=null, $limit=null);
        $this->view->assign("dados", $dados);
    }

    public function inserirAction ()
    {
        $dados = $this->_getAllParams();
        $dadosColaborador = new Application_Model_EmailColaboradores();
        $dadosColaborador->insertColaborador($dados, $this->usuario['codCliente']);
        $this->redirect("/email-colaboradores");
    }

}

