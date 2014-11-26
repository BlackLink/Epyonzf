<?php

class EnvioRelatoriosController extends Zend_Controller_Action
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
        $dadosUsuario = new Application_Model_EnvioRelatorios();
        $nomeUsuario = $dadosUsuario->selectNome($this->usuario['idLogin']);
        $nomeUsuario = explode(" ", $nomeUsuario['nome']);
        $this->view->assign("name_user", $nomeUsuario[0]);
    }

    public function indexAction()
    {
        $modelColaboradores = new Application_Model_EnvioRelatorios();
        $projBd = new Application_Model_Projetos();
        
        $dadosColaboradores = $modelColaboradores->selectColaboradores($this->usuario['idLogin']);
        $dadosProjeto = $modelColaboradores->selectProjetos($this->usuario['idLogin']);
        $dadosProjetoBd = $projBd->selectProjetoBD($this->usuario['idLogin']);
        
        $this->view->assign("dadosRelatorioBd", $dadosProjetoBd);
        $this->view->assign("dadosColaborador", $dadosColaboradores);
        $this->view->assign("dadosRelatorio", $dadosProjeto);
    }


}

