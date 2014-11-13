<?php

class Application_Model_EmailColaboradores
{
    
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
   
   public function selectColaborador($where = null, $order = null, $limit = null)
   {
      $dao = new Application_Model_DbTable_EmailColaboradores();
      $select = $dao->select()->from($dao)->order('nomeCompleto')->limit($limit);
      if(!is_null($where)){
         $select->where($where);
      }
      return $dao->fetchAll($select)->toArray();
   }
    
        
}

