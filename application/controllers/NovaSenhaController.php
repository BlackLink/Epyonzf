<?php

class NovaSenhaController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }
    
    public function erroAction()
    {
        
    }

    public function indexAction()
    {
        $dados = $this->getAllParams();
        
        $dbRec = new Application_Model_Recupera();
        
        $status = $dbRec->selectRecupera($dados);
        
        if ($status == 1)
            $this->redirect ('nova-senha/erro');
    }
    
    public function atualizaAction ()
    {   
        $dados = $this->_getAllParams();
        
        if (strcmp($dados['tNovaSenha'], $dados['tRepitaSenha']))
            $this->view->assign("status", 0);
         
        else
        {
            $dados['tSenha'] = sha1($dados['tSenhaAtual']);
            $mdLogin = new Application_Model_NovaSenha();
            $dbRec = new Application_Model_Recupera();
            $dbRec->updateRecupera($dados);
            $status = $mdLogin->updateSenha($dados);

            $this->view->assign("status", $status);
        }
        
    }
}
