<?php

class CadastroProjetosController extends Zend_Controller_Action
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
    }

    public function indexAction()
    {
        
    }
    
    public function recuperaAction ()
    {
        $dadosColaborador = new Application_Model_EmailCadastroColaboradores();
        $idProj = $this->getParam('idProjeto');
        $dados = $dadosColaborador->selectColaborador($idProj);
        $this->view->assign("idProjGet", $idProj);
        $this->view->assign("dados", $dados);
    }

}

