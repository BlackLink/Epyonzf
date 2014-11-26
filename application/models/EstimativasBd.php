<?php

class Application_Model_EstimativasBd
{
    
    public function selectEstimativaBd($idUser)
    {
        $dbProjeto = new Application_Model_DbTable_ProjetosBd();
        
        $dbCliente = new Application_Model_DbTable_Cliente();
      
        $codCliente = $dbCliente->select()->from($dbCliente)->where('codLogin ='.$idUser);
        
        $codCliente = $dbCliente->fetchRow($codCliente)->toArray();
        
        $codProj = $dbProjeto->select()->from($dbProjeto)->where("codCliente =".$codCliente['idCliente'])
                ->where('excluido = ?', 'N');
        
        $codProj = $dbCliente->fetchAll($codProj)->toArray();
        
        $dbGeral = Zend_Db_Table::getDefaultAdapter();
        
        $flag = array ();
        $flag1 = count($codProj);
        $flag2 = 0;
        
        while ($flag2 < $flag1)
        {
            $select = $dbGeral->select()->from('estimativas_bd')
                    ->from('projetos_bd', array('nome'))->where('codProjBd ='.$codProj[$flag2]['idProjetoBd'])
                    ->where('codCliente ='.$codCliente['idCliente'])->order('nome');
            
            $flag [$flag2] = $dbGeral->fetchAll($select);
            
            $flag2++;
        }
        
        return $flag;
    }
}

