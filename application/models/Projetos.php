<?php

class Application_Model_Projetos
{
    
    public function selectProjeto ($where=null, $order=null, $limit=null)
    {
        $db = new Application_Model_DbTable_Projetos();
        
        $select = $db->select()->from($db)->order($order)->limit($order);
        
        return $db->fetchAll($select)->toArray();
    }
    
    public function insertProjeto ()
    {
        
    }
    
}

