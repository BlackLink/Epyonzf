<?php

class Application_Model_AlterarSenha
{
    public function selectAlterarSenha ($request)
    {
        $dao = new Application_Model_DbTable_Cliente();
        $select = $dao->select()->from($dao, array('E_mail'))->where('E_mail = ?',$request);
        return $dao->fetchAll($select)->toArray();
    }
}
