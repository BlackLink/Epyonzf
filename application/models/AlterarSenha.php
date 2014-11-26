<?php

class Application_Model_AlterarSenha
{
    public function selectAlterarSenha ($request)
    {
        $dao = new Application_Model_DbTable_Cliente();
        $select = $dao->select()->from($dao, array('E_mail'))->where('E_mail = ?',$request);
        return $dao->fetchAll($select)->toArray();
    }
    
    public function enviaEmail ($email)
    {
        $dbCliente = new Application_Model_DbTable_Cliente();
        $dbLogin = new Application_Model_DbTable_Login();
        $dbRecupera = new Application_Model_Recupera();
        
        $codLoginUser = $dbCliente->select()->from($dbCliente, array('codLogin'))
                       ->where('E_mail = ?', $email);
        
        $codLoginUser = $dbCliente->fetchRow($codLoginUser)->toArray();
        
        $dadosUser = $dbLogin->select()->from($dbLogin);
        
        $dadosUser = $dbLogin->fetchRow($dadosUser)->toArray();
        
        $chave = '';
        $sopaLetras = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        srand((double)microtime()*1000000);
        for($i=0; $i < 30; $i++) {
            $chave .= $sopaLetras[rand()%strlen($sopaLetras)];
        }
	
        $dadosRec = array ('chave' => $chave, 'usada' => 'N', 'codLogin'=> $codLoginUser['codLogin']);
        
        $dbRecupera->insertRecupera($dadosRec);
        
        // Envio de Mensagem 
        
        $mensagem = 'Você solicitou a alteração de sua senha,'
                   .'por favor, clique no link a seguir para alterar sua senha'
                   .'(se você não solicitou a alteração da sua senha, por favor, ignore este e-mail):'
                   .' http://localhost/Epyonzf/public/nova-senha/index/ch/'.$chave.'/id/'.$codLoginUser['codLogin'];
        
        $config = array('auth' => 'login',
		'username' => 'versalius.it@gmail.com',
		'password' => 'aclm123versalius',
		'port' => '465',
		'ssl' => 'ssl'
		);

        $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);

        $mail = new Zend_Mail('UTF-8');
        $mail->setBodyText($mensagem);
        $mail->setFrom('versalius.it@gmail.com', 'Versalius');
        $mail->addTo($email, $dadosUser['user']);
        $mail->setSubject('Alteração de Senha Epyon');
        
        if ($mail->send($transport))
            return 1;
        else
            return 0;
       
    }
}
