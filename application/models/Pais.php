<?php

class Application_Model_Pais
{
    
    private $dbTablePais;
    
    public function select($where = null, $order = null, $limit = null)
    {
        $this->dbTablePais = new Application_Model_DbTable_Pais();
        $select = $this->dbTablePais->select()
                ->from($this->dbTablePais)->order($order)->limit($limit);

        if(!is_null($where)){
            $select->where($where);
        }

        return $this->dbTablePais->fetchAll($select)->toArray();
    }
//    
//    public function find($id)
//    {
//        $this->dbTablePais = new Application_Model_Pais();
//        $arr = $this->dbTablePais->find($id)->toArray();
//        return $arr[0];
//    }
//    
//    public function insert(array $request){
//        
//        $this->dbTablePais = new Application_Model_Pais();
//        
//        $dadosPais = array(
//            'ta_nomePais' => $request['nomePais'],
//            'ta_nacionalidade' => $request['nacionalidade']
//        );
//        
//        $this->dbTablePais->insert($dadosPais); //Insere
//
//        return;
//    }
//    
//    public function update(array $request)
//    {
//        $this->dbTablePais = new Application_Model_Pais();
//        
//        $dadosPais = array(
//            'ta_nomePais' => $request['nomePais'],
//            'ta_nacionalidade' => $request['nacionalidade']
//        );
//        
//        $wherePais= $this->dbTablePais->getAdapter()
//                ->quoteInto('ta_idPais = ?', $request['idPais']);
//        
//        $this->dbTablePais->update($dadosPais, $wherePais);
//    }
//    
//    public function delete($idAlbum)
//    {
//        $this->dbTablePais = new Application_Model_DbTable_Album();       
//                
//        $whereAlbum= $this->dbTablePais->getAdapter()
//                ->quoteInto('ta_id = ?', $idAlbum);
//        
//        $this->dbTablePais->delete($whereAlbum);
//    }
    
}

