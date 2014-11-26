<?php

class RelatoriosController extends Zend_Controller_Action
{

    var $usuario;
    
    public function init()
    {
         /* Initialize action controller here */
        
        require_once('../library/fpdf/fpdf.php');
        
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/index');
        }

        $auth = Zend_Auth::getInstance();
        
        if ($auth->hasIdentity()) {
            $identity = $auth->getIdentity();
            $this->usuario = get_object_vars($identity);
        }
        $this->_helper->layout->setLayout('pos-login');
        $dadosUsuario = new Application_Model_Relatorios();
        $nomeUsuario = $dadosUsuario->selectNome($this->usuario['idLogin']);
        $nomeUsuario = explode(" ", $nomeUsuario['nome']);
        $this->view->assign("name_user", $nomeUsuario[0]);
    }

    public function indexAction()
    {
        $dadosRelatorio = new Application_Model_Relatorios();
        $dbProjBd = new Application_Model_Projetos();
        
        $dados = $dadosRelatorio->selectRelatorio($this->usuario['idLogin']);
        $dados2 = $dbProjBd->selectProjetoBD($this->usuario['idLogin']);
        
        $this->view->assign("dados", $dados);
        $this->view->assign("dados2", $dados2);
    }
    
    public function pdfAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        
        $idProj = $this->_getParam('idProjeto');
        $op = $this->getParam('opcao');
        
        $mdProj = new Application_Model_Projetos();
        $mdRelat = new Application_Model_Relatorios();
        
        $dadosProj = $mdProj->selectProjetoPdf($idProj);
        
        $mdRelat->pdfRelatorio($dadosProj, $op);
    }
    
    public function pdfbdAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        
        $idProj = $this->_getParam('idProjeto');
        $op = $this->getParam('opcao');
        
        $mdProj = new Application_Model_Projetos();
        $mdRelat = new Application_Model_Relatorios();
        
        $dadosProj = $mdProj->selectProjetoPdfBd($idProj);
        $mdRelat->pdfRelatorioBd($dadosProj, $op);
    }
    
    public function manualAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        $mdRelat = new Application_Model_Relatorios();
        $mdRelat->downloadManual();
    }
}

