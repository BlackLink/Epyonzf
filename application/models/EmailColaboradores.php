<?php

class Application_Model_EmailColaboradores
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
    
   public function insertColaborador (array $request, $codCliente)
   {
      $dao = new Application_Model_DbTable_EmailColaboradores();
      $dados = array(
         'nomeCompleto' => $request['tNomeColaborador'],
         'email' => $request['tEmailColaborador'],
         'codCliente' => $codCliente
      );
      return $dao->insert($dados);
   }
   
   public function selectColaborador($idUser)
   {
      $dao = new Application_Model_DbTable_EmailColaboradores();
      
      $dbCliente = new Application_Model_DbTable_Cliente();
      
      $codCliente = $dbCliente->select()->from($dbCliente)->where('codLogin ='.$idUser);
      
      $codCliente = $dbCliente->fetchRow($codCliente)->toArray();
      
      $select = $dao->select()->from($dao)->order('nomeCompleto')->where("codCliente =".$codCliente['idCliente']);
      
      return $dao->fetchAll($select)->toArray();
   }
    
   public function selectUpColaborador($idUser)
   {
      $dao = new Application_Model_DbTable_EmailColaboradores();
      
      $dbCliente = new Application_Model_DbTable_Cliente();
      
      $codCliente = $dbCliente->select()->from($dbCliente)->where('codLogin ='.$idUser);
      
      $codCliente = $dbCliente->fetchRow($codCliente)->toArray();
      
      return $codCliente['idCliente'];
   }    
}

