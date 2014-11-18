<?php

class Application_Model_Estimativas
{   
    
    public function selectNome ($idUser)
    {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        
        $codCliente = $db->select()->from('cliente')->where('codLogin ='.$idUser);
        
        $codCliente = $db->fetchRow($codCliente);
        
        $codClientePf = $db->select()->from('cliente_pf', array('nome'))->where('codCliente ='.$codCliente['idCliente']);
        
        $codClientePf = $db->fetchRow($codClientePf);
        
        $codClientePj = $db->select()->from('cliente_pj', array('nome'))->where('codCliente ='.$codCliente['idCliente']);
        
        $codClientePj = $db->fetchRow($codClientePj);
        
        if ($codClientePf == true)
            return $codClientePf;
        else if ($codClientePj == true)
            return $codClientePj;
    }  
    
    public function selectEstimativas ($idUser)
    {
        $dbEstimativas = new Application_Model_DbTable_Estimativas();
        $dbProjeto = new Application_Model_DbTable_Projetos();
        
        $dbCliente = new Application_Model_DbTable_Cliente();
      
        $codCliente = $dbCliente->select()->from($dbCliente)->where('codLogin ='.$idUser);
        
        $codCliente = $dbCliente->fetchRow($codCliente)->toArray();
        
        $codProj = $dbProjeto->select()->from($dbProjeto)->where("codCliente =".$codCliente['idCliente']);
        
        $codProj = $dbCliente->fetchAll($codProj)->toArray();
        
        $dbGeral = Zend_Db_Table::getDefaultAdapter();
        
        $flag = array ();
        $flag1 = count($codProj);
        $flag2 = 0;
        
        while ($flag2 < $flag1)
        {
            $select = $dbGeral->select()->from('estimativas')
                    ->from('projetos', array('nome'))->where('codProjeto ='.$codProj[$flag2]['idProjetos'])
                    ->where('codCliente ='.$codCliente['idCliente'])->order('nome');
            
            $flag [$flag2] = $dbGeral->fetchRow($select);
            
            $flag2++;
        }
        
        return $flag;
        
    }
}

