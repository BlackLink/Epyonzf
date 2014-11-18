<?php

class Application_Model_Projetos
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
    
    public function selectProjeto ($idUser)
    {
        $db = new Application_Model_DbTable_Projetos();
        
        $dbCliente = new Application_Model_DbTable_Cliente;
        
        $codCliente = $dbCliente->select()->from($dbCliente)->where('codLogin ='.$idUser);
        
        $codCliente = $dbCliente->fetchRow($codCliente)->toArray();
        
        $select = $db->select()->from($db)->order('nome')->where("codCliente =".$codCliente['idCliente']);
        
        return $db->fetchAll($select)->toArray();
    }
    
    public function insertProjeto (array $request, $idUser)
    {
        $dbEspDesenvProj = new Application_Model_DbTable_EspecifDesenProj();
        $dbEspProj = new Application_Model_DbTable_EspecifProjetos();
        $dbProj = new Application_Model_DbTable_Projetos();
        $dbCliente = new Application_Model_DbTable_Cliente();
        $dbEstimativa = new Application_Model_DbTable_Estimativas();
        
        $codCliente = $dbCliente->select()->from($dbCliente)->where('codLogin ='.$idUser);
        
        $codCliente = $dbCliente->fetchRow($codCliente)->toArray();
        
        $dadosEspDesenvProj = array 
        (
            'col_nv_5' => $request['tDesenNivel5'],
            'col_nv_4' => $request['tDesenNivel4'],
            'col_nv_3' => $request['tDesenNivel3'],
            'col_nv_2' => $request['tDesenNivel2'],
            'col_nv_1' => $request['tDesenNivel1'],
            
            'quant_hora_nv5' => $request['tHoraNivel5'],
            'quant_hora_nv4' => $request['tHoraNivel4'],
            'quant_hora_nv3' => $request['tHoraNivel3'],
            'quant_hora_nv2' => $request['tHoraNivel2'],
            'quant_hora_nv1' => $request['tHoraNivel1'],
            
            'valor_hora_nv5' => $request['tValorNivel5'],
            'valor_hora_nv4' => $request['tValorNivel4'],
            'valor_hora_nv3' => $request['tValorNivel3'],
            'valor_hora_nv2' => $request['tValorNivel2'],
            'valor_hora_nv1' => $request['tValorNivel1']
        );
        
        $codEspDesenvProj = $dbEspDesenvProj->insert($dadosEspDesenvProj);
        
        $dadosEspProj = array 
        (
            'custoUsuario' => $request['tExtraCusto'],
            'esforcoUsuario' => $request['tExtraEsf'],
            'prazoUsuario' => $request['tExtraTempo'],
            'outros_custos' => $request['tOutrosCustos'],
            'num_casos_usos' => $request['tNumCasoUso'],
            'fluxos_casos_usos' => $request['tFluxoCasoUso'],
            'linhas_casos_uso' => $request['tLinhaCasoUso'],
            'num_interfaces' => $request['tNumInterface'],
            'num_sub_interfaces' => $request['tNumSubInterface'],
            'num_elem_interfaces' => $request['tNumElemInterface'],
            'num_mod_usuarios' => $request['tNumModUsers'],
            'num_saidas' => $request['tNumSaidas'],
            'num_geradores_result' => $request['tNumGeradoresResult']
        );
        
        $codEspProj = $dbEspProj->insert($dadosEspProj);
        
        $dadosProj = array 
        (
            'nome' => $request['tNomeProjeto'],
            'descricao' => $request['tDescrProj'],
            'dataInicio' => $request['tDataIni'],
            'dataFim' => $request['tDataFim'],
            'tipo_projeto' => $request['tRadio'],
            'codCliente' => $codCliente['idCliente'],
            'codEspProj' => $codEspProj,
            'codEspDesenvProj' => $codEspDesenvProj
        );
        
        $codProj = $dbProj->insert($dadosProj);
        
        /**
         * Cálculo das estimativas
         */
        
        $custo = (($request['tHoraNivel5']*$request['tValorNivel5']*$request['tDesenNivel5']*8)
                + ($request['tHoraNivel4']*$request['tValorNivel4']*$request['tDesenNivel4']*8)
                + ($request['tHoraNivel3']*$request['tValorNivel3']*$request['tDesenNivel3']*8)
                + ($request['tHoraNivel2']*$request['tValorNivel2']*$request['tDesenNivel2']*8)
                + ($request['tHoraNivel1']*$request['tValorNivel1']*$request['tDesenNivel1']*8)
                + $request['tOutrosCustos']);
        
        $produtividade = (($request['tDesenNivel1']*5)+($request['tDesenNivel1']*4)
                        + ($request['tDesenNivel1']*3)+($request['tDesenNivel1']*2)
                        + ($request['tDesenNivel1']*1));
        
        $pontosFuncao = (($request['tNumCasoUso']*7)+($request['tFluxoCasoUso']*10)+($request['tLinhaCasoUso']*15)
                        +($request['tNumInterface']*5)+($request['tNumSubInterface']*7)+($request['tNumElemInterface']*10)
                        +($request['tNumModUsers']*4)+($request['tNumSaidas']*4)+($request['tNumGeradoresResult']*5));
        
        $esforco = $pontosFuncao/$produtividade;
        
        $prazo = ($esforco*3)/($produtividade*5);
        
        $dadosEstimativas = array ('custo' => $custo, 'tempo' => $prazo, 'esforco' => $esforco, 'codProjeto' => $codProj);
        
        $dbEstimativa->insert($dadosEstimativas);
        
    }
    
    public function updateProjeto (array $request)
    {
        
        $dbEspDesenvProj = new Application_Model_DbTable_EspecifDesenProj();
        $dbEspProj = new Application_Model_DbTable_EspecifProjetos();
        $dbProj = new Application_Model_DbTable_Projetos();
        $dbEstimativa = new Application_Model_DbTable_Estimativas();
        
        $dadosProjAll = $dbProj->select()->from($dbProj)->where("idProjetos =".$request['tHiddenProj']);
        
        $dadosProjAll = $dbProj->fetchRow()->toArray();
        
        $dadosEspDesenvProj = array 
        (
            'col_nv_5' => $request['tDesenNivel5'],
            'col_nv_4' => $request['tDesenNivel4'],
            'col_nv_3' => $request['tDesenNivel3'],
            'col_nv_2' => $request['tDesenNivel2'],
            'col_nv_1' => $request['tDesenNivel1'],
            
            'quant_hora_nv5' => $request['tHoraNivel5'],
            'quant_hora_nv4' => $request['tHoraNivel4'],
            'quant_hora_nv3' => $request['tHoraNivel3'],
            'quant_hora_nv2' => $request['tHoraNivel2'],
            'quant_hora_nv1' => $request['tHoraNivel1'],
            
            'valor_hora_nv5' => $request['tValorNivel5'],
            'valor_hora_nv4' => $request['tValorNivel4'],
            'valor_hora_nv3' => $request['tValorNivel3'],
            'valor_hora_nv2' => $request['tValorNivel2'],
            'valor_hora_nv1' => $request['tValorNivel1']
        );
        
        $whereEspDesenvProj = $dbEspDesenvProj->getAdapter()->quoteInto("idEsp_Desenv_Proj = ?",$dadosProjAll['codEspDesenvProj']);
        
        $dbEspDesenvProj->update($dadosEspDesenvProj, $whereEspDesenvProj);
        
        $dadosEspProj = array 
        (
            'custoUsuario' => $request['tExtraCusto'],
            'esforcoUsuario' => $request['tExtraEsf'],
            'prazoUsuario' => $request['tExtraTempo'],
            'outros_custos' => $request['tOutrosCustos'],
            'num_casos_usos' => $request['tNumCasoUso'],
            'fluxos_casos_usos' => $request['tFluxoCasoUso'],
            'linhas_casos_uso' => $request['tLinhaCasoUso'],
            'num_interfaces' => $request['tNumInterface'],
            'num_sub_interfaces' => $request['tNumSubInterface'],
            'num_elem_interfaces' => $request['tNumElemInterface'],
            'num_mod_usuarios' => $request['tNumModUsers'],
            'num_saidas' => $request['tNumSaidas'],
            'num_geradores_result' => $request['tNumGeradoresResult']
        );
        
        $whereEspProj = $dbEspProj->getAdapter()->quoteInto("idEsp_Projetos = ?",$dadosProjAll['codEspProj']);
        
        $dbEspProj->update($dadosEspProj, $whereEspProj);
        
        $dadosProj = array 
        (
            'nome' => $request['tNomeProjeto'],
            'descricao' => $request['tDescrProj'],
            'dataInicio' => $request['tDataIni'],
            'dataFim' => $request['tDataFim'],
            'tipo_projeto' => $request['tRadio'],
            'codCliente' => $dadosProjAll['codCliente'],
            'codEspProj' => $dadosProjAll['codEspProj'],
            'codEspDesenvProj' => $dadosProjAll['codEspDesenvProj']
        );
        
        $whereProj = $dbProj->getAdapter()->quoteInto("idProjetos = ?",$request['tHiddenProj']);
        
        $dbProj->update($dadosProj, $whereProj);
        
        /**
         * Cálculo das estimativas
         */
        
        $custo = (($request['tHoraNivel5']*$request['tValorNivel5']*$request['tDesenNivel5']*8)
                + ($request['tHoraNivel4']*$request['tValorNivel4']*$request['tDesenNivel4']*8)
                + ($request['tHoraNivel3']*$request['tValorNivel3']*$request['tDesenNivel3']*8)
                + ($request['tHoraNivel2']*$request['tValorNivel2']*$request['tDesenNivel2']*8)
                + ($request['tHoraNivel1']*$request['tValorNivel1']*$request['tDesenNivel1']*8)
                + $request['tOutrosCustos']);
        
        $produtividade = (($request['tDesenNivel1']*5)+($request['tDesenNivel1']*4)
                        + ($request['tDesenNivel1']*3)+($request['tDesenNivel1']*2)
                        + ($request['tDesenNivel1']*1));
        
        $pontosFuncao = (($request['tNumCasoUso']*7)+($request['tFluxoCasoUso']*10)+($request['tLinhaCasoUso']*15)
                        +($request['tNumInterface']*5)+($request['tNumSubInterface']*7)+($request['tNumElemInterface']*10)
                        +($request['tNumModUsers']*4)+($request['tNumSaidas']*4)+($request['tNumGeradoresResult']*5));
        
        $esforco = $pontosFuncao/$produtividade;
        
        $prazo = ($esforco*3)/($produtividade*5);
        
        $select = $dbEstimativa->select()->from($dbEstimativa)->where('codProjeto ='.$request['tHiddenProj']);
        
        $select = $dbEstimativa->fetchRow()->toArray();
        
        $dadosEstimativas = array ('custo' => $custo, 'tempo' => $prazo, 'esforco' => $esforco, 'codProjeto' => $request['tHiddenProj']);
        
        $whereEstimativa = $dbEstimativa->getAdapter()->quoteInto('idEstimativas = ?', $select['idEstimativas']);
        
        $dbEstimativa->update($dadosEstimativas, $whereEstimativa);
    }
}

