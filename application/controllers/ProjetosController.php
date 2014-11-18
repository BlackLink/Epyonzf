<?php

class ProjetosController extends Zend_Controller_Action
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
        $dadosUsuario = new Application_Model_PosLogin();
        $nomeUsuario = $dadosUsuario->selectNome($this->usuario['idLogin']);
        $this->view->assign("name_user", $nomeUsuario['nome']);
    }

    public function indexAction()
    {
        $db = new Application_Model_Projetos;
        $dados = $db->selectProjeto($this->usuario['idLogin']);
        $this->view->assign("dados", $dados);
    }

    public function inserirAction ()
    {
        $dados = $this->_getAllParams();
        
        $dbProj = new Application_Model_Projetos();
        
        $dbProj->insertProjeto($dados, $this->usuario['idLogin']);
        
        $this->redirect("/projetos");
    }
}

