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
        $nomeUsuario = explode(" ", $nomeUsuario['nome']);
        $this->view->assign("name_user", $nomeUsuario[0]);
    }

    public function indexAction()
    {
        $db = new Application_Model_Projetos();
        $dados = $db->selectProjeto($this->usuario['idLogin']);
        $dados2 = $db->selectProjetoBD($this->usuario['idLogin']);
        $this->view->assign("dados", $dados);
        $this->view->assign("dados2", $dados2);
    }

    public function inserirAction ()
    {
        $dados = $this->_getAllParams();
        
        $dbProj = new Application_Model_Projetos();
        
        $dbProj->insertProjeto($dados, $this->usuario['idLogin']);
        
        $this->redirect("/estimativas");
    }
    
    public function bdAction ()
    {
        $dados = $this->_getAllParams();
        
        $mdProj = new Application_Model_Projetos();
        
        $mdProj->insertProjetoBD($dados, $this->usuario['idLogin']);
        
        $this->redirect("/estimativas");
    }
}

