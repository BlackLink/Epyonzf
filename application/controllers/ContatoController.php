<?php

class ContatoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
       
    }
    
    public function enviarAction()
    {
        $form = $this->_getAllParams();
        
        $usernme = $form['tNome'];
        $msg = $form['tMsg'];
        $usermail = $form['tMail'];
        
        $config = array('auth' => 'login',
		'username' => 'versalius.it@gmail.com',
		'password' => 'aclm123versalius',
		'port' => '465',
		'ssl' => 'ssl'
		);

        $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);

        $mail = new Zend_Mail('UTF-8');
        $mail->setBodyText($msg);
        $mail->setFrom($usermail, $usernme);
        $mail->addTo('versalius.it@gmail.com', 'Versalius');
        $mail->setSubject('Fale Conosco do Site');
        $mail->send($transport);
        
        $this->_redirect('/contato');
        
    }
}