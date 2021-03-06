<?php

class Application_Model_EmailCadastroColaboradores
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
   
   public function selectColaborador($idCol)
   {
       
      $dao = new Application_Model_DbTable_EmailColaboradores();
      $select = $dao->select()->from($dao)->where("idEmail_Colaboradores=".$idCol);
      
      return $dao->fetchAll($select)->toArray();
   }
   
   public function updateColaborador (array $request)
   {
       $dao = new Application_Model_DbTable_EmailColaboradores();
       
       $dados = array (
            'nomeCompleto' => $request['tNomeColaborador'],
            'email' => $request['tEmailColaborador'],
            'excluido' => 'N'
       );
        
        $where = $dao->getAdapter()->quoteInto("idEmail_Colaboradores = ?", $request['tHiddenCol']);
        $dao->update($dados, $where);
   }
   
   public function deleteColaborador ($idCol)
   {
       $dao = new Application_Model_DbTable_EmailColaboradores();
       $select = $dao->select()->from($dao)->where("idEmail_Colaboradores=".$idCol);
       $select = $dao->fetchRow($select)->toArray();
       
       $dados = array (
            'nomeCompleto' => $select['nomeCompleto'],
            'email' => $select['email'],
            'excluido' => 'S'
       );
        
        $where = $dao->getAdapter()->quoteInto("idEmail_Colaboradores = ?", $idCol);
        $dao->update($dados, $where);
   }
}

