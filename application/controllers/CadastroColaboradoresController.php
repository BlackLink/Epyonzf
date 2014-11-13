<?php

class CadastroColaboradoresController extends Zend_Controller_Action
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
        $idCol = $this->getParam('idCol');
        $dados = $dadosColaborador->selectColaborador($idCol);
        $this->view->assign("idColGet", $idCol);
        $this->view->assign("dados", $dados);
    }
    
    public function updateAction()
    {
        $dados = $this->_getAllParams(); 
        $dadosColaborador = new Application_Model_EmailCadastroColaboradores();
        $dadosColaborador->updateColaborador($dados);
        $this->redirect("/email-colaboradores");
    }
    
    public function deleteAction ()
    {
        $dados = $this->getParam('idCol');
        $dadosColaborador = new Application_Model_EmailCadastroColaboradores();
        $dadosColaborador->deleteColaborador($dados);
        $this->redirect('/email-colaboradores');
    }
}

