<?php

class AlterarSenhaController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        
    }
    public function consultaAction ()
    {
        $emailCliente = new Application_Model_AlterarSenha();
        $nomeEmail = $this->getParam('tUserMail');
       
        $dados = $emailCliente->selectAlterarSenha($nomeEmail);
        
        if (isset($dados[0]['E_mail']))
        {
            $this->view->assign("sucesso", 1);
        }
        else
        {
            $this->view->assign("falha", 0);
        }
        
    }
}
