<?php

class Application_Model_PosLogin
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
}

