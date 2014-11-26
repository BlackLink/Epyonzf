<?php

class Application_Model_EnvioRelatorios
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
    
    public function selectColaboradores ($idUser)
    {
        $dao = new Application_Model_DbTable_EmailColaboradores();
      
        $dbCliente = new Application_Model_DbTable_Cliente();

        $codCliente = $dbCliente->select()->from($dbCliente)->where('codLogin ='.$idUser);

        $codCliente = $dbCliente->fetchRow($codCliente)->toArray();

        $select = $dao->select()->from($dao)->order('nomeCompleto')->where("codCliente =".$codCliente['idCliente'])
                ->where('excluido = ?', 'N');

        return $dao->fetchAll($select)->toArray();
    }
    
    public function selectProjetos ($idUser)
    {
        $db = new Application_Model_DbTable_Projetos();
        
        $dbCliente = new Application_Model_DbTable_Cliente;
        
        $codCliente = $dbCliente->select()->from($dbCliente)->where('codLogin ='.$idUser);
        
        $codCliente = $dbCliente->fetchRow($codCliente)->toArray();
        
        $select = $db->select()->from($db)->order('nome')->where("codCliente =".$codCliente['idCliente'])
                ->where('excluido = ?', 'N');
        
        return $db->fetchAll($select)->toArray();
    }
}

