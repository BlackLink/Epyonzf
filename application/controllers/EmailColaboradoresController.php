<?php

class EmailColaboradoresController extends Zend_Controller_Action
{
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
        $dadosUsuario = new Application_Model_EmailColaboradores();
        $nomeUsuario = $dadosUsuario->selectNome($this->usuario['idLogin']);
        $nomeUsuario = explode(" ", $nomeUsuario['nome']);
        $this->view->assign("name_user", $nomeUsuario[0]);
        
    }

    public function indexAction()
    {
        $dadosColaborador = new Application_Model_EmailColaboradores();
        $dados = $dadosColaborador->selectColaborador($this->usuario['idLogin']);
        $this->view->assign("dados", $dados);
    }

    public function inserirAction ()
    {
        $dados = $this->_getAllParams();
        
        $dadosColaborador = new Application_Model_EmailColaboradores();
        
        $idCliente = $dadosColaborador->selectUpColaborador($this->usuario['idLogin']);
        
        $dadosColaborador->insertColaborador($dados, $idCliente);
        
        $this->redirect("/email-colaboradores");
    }

}

