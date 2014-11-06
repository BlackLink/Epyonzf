<?php

class Application_Model_AutenticacaoUsuario
{
    public static function loginAdm($tUserName,$tUserPassword){
       //Criptografando a senha
       $senhaSha1 = sha1($tUserPassword);
       
       $db = Zend_Db_Table_Abstract::getDefaultAdapter();
       //O USUARIO DE LOGIN ESTA COMO 'EMAIL' MAS PODE SER OUTRO LOGIN QUALQUER
       $adapter = new Zend_Auth_Adapter_DbTable($db,
               'login',   //nome da tabela
               'user',    //coluna da tabela
               'password' //coluna da tabela
               );
       
       $adapter
               ->SetIdentity($tUserName)
               ->SetCredential($senhaSha1);
      //////////////////
       $auth = Zend_Auth::getInstance();
       $result = $auth->authenticate($adapter);
       
       if($result->isValid()){
           $data = $adapter->getResultRowObject(null,'password');
           $auth->getStorage()->write($data);
           
           return TRUE;
       }else{
           return FALSE;
       }
   }   
}
