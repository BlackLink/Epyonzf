<?php

class Application_Model_CadastroProjetos
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
    
    public function selectProjeto ($idProj)
    {   
        $dadosGerais = array ();
        
        $dbProj = new Application_Model_DbTable_Projetos();
        $dbEspProj = new Application_Model_DbTable_EspecifProjetos();
        $dbEspDesenvProj = new Application_Model_DbTable_EspecifDesenProj();
        
        $dadosProj = $dbProj->select()->from($dbProj)->where('idProjetos ='.$idProj);
        
        $dadosProj = $dbProj->fetchRow($dadosProj)->toArray();
        
        $dadosEspProj = $dbEspProj->select()->from($dbEspProj)->where('idEsp_Projetos ='.$dadosProj['codEspProj']);
        
        $dadosEspProj = $dbEspProj->fetchRow($dadosEspProj)->toArray();
        
        $dadosEspDesenvProj = $dbEspDesenvProj->select()->from($dbEspDesenvProj)->where('idEsp_Desenv_Proj ='.$dadosProj['codEspDesenvProj']);
        
        $dadosEspDesenvProj = $dbEspDesenvProj->fetchRow($dadosEspDesenvProj)->toArray();
        
        $dadosGerais[0] = $dadosProj;
        $dadosGerais[1] = $dadosEspProj;
        $dadosGerais[2] = $dadosEspDesenvProj;
        
        return $dadosGerais;
    }
}

