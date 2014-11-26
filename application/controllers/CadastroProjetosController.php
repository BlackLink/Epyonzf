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
        $dadosUsuario = new Application_Model_CadastroProjetos();
        $nomeUsuario = $dadosUsuario->selectNome($this->usuario['idLogin']);
        $nomeUsuario = explode(" ", $nomeUsuario['nome']);
        $this->view->assign("name_user", $nomeUsuario[0]);
    }

    public function indexAction()
    {
        
    }
    
    public function recuperaAction ()
    {
        $dadosColaborador = new Application_Model_CadastroProjetos();
        $idProj = $this->getParam('idProjeto');
        $dados = $dadosColaborador->selectProjeto($idProj);
        $this->view->assign("idProjGet", $idProj);
        $this->view->assign("dados", $dados);
    }
    
    public function updateAction ()
    {
        $dados = $this->_getAllParams();
        $dadosProjeto = new Application_Model_Projetos();
        $dadosProjeto->updateProjeto($dados);
        $this->redirect("/estimativas");
        
    }
    
    public function deleteAction ()
    {
        $dados = $this->getParam('idProjeto');
        $mdProj = new Application_Model_Projetos();
        $mdProj->deleteProjeto($dados);
        $this->redirect('/projetos');
    }
    
    public function bdAction()
    {
        //somente layout
    }
    
    public function recuperabdAction ()
    {
        $db = new Application_Model_Projetos();
        $idProj = $this->getParam('idProjeto');
        $dados = $db->selectProjetoBD2($idProj);
        $this->view->assign("idProjGet", $idProj);
        $this->view->assign("dados", $dados);
    }
    
    public function updatebdAction()
    {
        $dados = $this->_getAllParams();
        $dadosProjeto = new Application_Model_Projetos();
        $dadosProjeto->updateBD($dados);
        $this->redirect("/estimativas");
    }
    
    public function deletebdAction ()
    {
        $dados = $this->getParam('idProjeto');
        $mdProj = new Application_Model_Projetos();
        $mdProj->deleteBD($dados);
        $this->redirect('/projetos');
    }
}

