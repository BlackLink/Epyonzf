<?php

class Application_Model_Recupera
{
 
    public function insertRecupera ($dados)
    {
        $dbRec = new Application_Model_DbTable_Recupera();
        
        $dbRec->insert($dados);
    }
    
    public function updateRecupera ($request)
    {
       $dao = new Application_Model_DbTable_Recupera();
     
       $dados = array (
            'chave' => $request['ch'],
            'usada' => 'S',
            'codLogin' => $dados['id']
       );
        
        $where = $dao->getAdapter()->quoteInto("chave = ?", $request['ch']);
        $dao->update($dados, $where);
    }
    
    public function selectRecupera ($dados)
    {
        $dbRec = new Application_Model_DbTable_Recupera();
        $ch = $dados['ch'];
        $chave = $dbRec->select()->from($dbRec)->where('chave = ?', $ch)->where('usada = ?', 'N');
        
        if ($chave == false)
            return 1;
        else
        {   
            $chave = $dbRec->fetchRow($chave)->toArray();
            return 0;
        }
    }
}
