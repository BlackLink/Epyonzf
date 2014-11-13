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
        
    }

    public function indexAction()
    {
        $db = new Application_Model_Projetos;
        $dados = $db->selectProjeto($where=null, $order=null, $limit=null);
        $this->view->assign("dados", $dados);
    }


}

