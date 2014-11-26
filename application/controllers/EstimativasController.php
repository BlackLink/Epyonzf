<?php

class EstimativasController extends Zend_Controller_Action
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
        $dadosUsuario = new Application_Model_Estimativas();
        $nomeUsuario = $dadosUsuario->selectNome($this->usuario['idLogin']);
        $nomeUsuario = explode(" ", $nomeUsuario['nome']);
        $this->view->assign("name_user", $nomeUsuario[0]);
    }

    public function indexAction()
    {
        $dadosEstimativas =  new Application_Model_Estimativas();
        $dbEst = new Application_Model_EstimativasBd();
        
        $dados = $dadosEstimativas->selectEstimativas($this->usuario['idLogin']);
        $dados2 = $dbEst->selectEstimativaBd($this->usuario['idLogin']);
        
        $this->view->assign("dados", $dados);
        $this->view->assign("dados2", $dados2);
    }
    
    public function graficosAction ()
    {
        
    }
}

