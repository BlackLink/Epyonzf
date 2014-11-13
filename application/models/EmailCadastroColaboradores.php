<?php

class Application_Model_EmailCadastroColaboradores
{
    
   public function selectColaborador($idCol)
   {
       
      $dao = new Application_Model_DbTable_EmailColaboradores();
      $select = $dao->select()->from($dao)->where("`idEmail-Colaboradores`=".$idCol);
      
      return $dao->fetchAll($select)->toArray();
   }
   
   public function updateColaborador (array $request)
   {
       $dao = new Application_Model_DbTable_EmailColaboradores();
       
       $dados = array (
            'nomeCompleto' => $request['tNomeColaborador'],
            'email' => $request['tEmailColaborador'],
       );
        
        $where = $dao->getAdapter()->quoteInto("`idEmail-Colaboradores` = ?", $request['tHiddenCol']);
        $dao->update($dados, $where);
   }
   
   public function deleteColaborador ($idCol)
   {
       $dao = new Application_Model_DbTable_EmailColaboradores();
       $where = $dao->getAdapter()->quoteInto("`idEmail-Colaboradores` = ?", $idCol);
       $dao->delete($where);
   }
}

