<?php

class AutenticacaoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $form = $this->_getAllParams();
        
                //AUTENTICAÇAO ADM                 
                $login = Application_Model_AutenticacaoUsuario::loginAdm($form['tUserName'],$form['tUserPassword']);
                if($login == true) {
                    $this->_helper->layout->setLayout('/pos-login');
                    $this->_redirect('/pos-login');
                }
                else {
                    $this->_redirect('/error-login');
                }
               
    }
    
    public function logoutAction(){
        $this->_helper->layout->setLayout('/layout');
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_redirect('/index');
    }
    
}
