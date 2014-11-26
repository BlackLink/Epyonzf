<?php

class Application_Model_NovaSenha
{
    
    public function updateSenha (array $request)
    {
        $dbLogin = new Application_Model_DbTable_Login();
        
        $dadosLogin = $dbLogin->select()->from($dbLogin)->where('user = ?',$request['tLogin']);
        
        if (is_null($dadosLogin = $dbLogin->fetchRow($dadosLogin)->toArray()) == 1)
            return 0;
        
        if (strcmp($request['tSenha'], $dadosLogin['password']))
            return 0;
        
        else
        {
            $novosDados = array ('password' => ($request['tSenha'] = sha1($request['tNovaSenha'])));
            
            $where = $dbLogin->getAdapter()->quoteInto('user = ?', $request['tLogin']);
            
            $dbLogin->update($novosDados, $where);
            
            return 1;
        }
    }
        
}

