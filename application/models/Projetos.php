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
        
        $select = $db->select()->from($db)->order('nome')->where("codCliente =".$codCliente['idCliente'])
                ->where('excluido = ?', 'N');
        
        return $db->fetchAll($select)->toArray();
    }
    
    public function selectProjetoPdf ($idProj)
    {
        $dadosGerais = array ();
        
        $dbProj = new Application_Model_DbTable_Projetos();
        $dbEspProj = new Application_Model_DbTable_EspecifProjetos();
        $dbEspDesenvProj = new Application_Model_DbTable_EspecifDesenProj();
        $dbEstimativa = new Application_Model_DbTable_Estimativas();
        
        $dadosProj = $dbProj->select()->from($dbProj)->where('idProjetos ='.$idProj);
        
        $dadosProj = $dbProj->fetchRow($dadosProj)->toArray();
        
        $dadosEspProj = $dbEspProj->select()->from($dbEspProj)->where('idEsp_Projetos ='.$dadosProj['codEspProj']);
        
        $dadosEspProj = $dbEspProj->fetchRow($dadosEspProj)->toArray();
        
        $dadosEspDesenvProj = $dbEspDesenvProj->select()->from($dbEspDesenvProj)->where('idEsp_Desenv_Proj ='.$dadosProj['codEspDesenvProj']);
        
        $dadosEspDesenvProj = $dbEspDesenvProj->fetchRow($dadosEspDesenvProj)->toArray();
        
        $dadosEstimativas = $dbEstimativa->select()->from($dbEstimativa)->where('codProjeto ='.$idProj);
        
        $dadosEstimativas = $dbEstimativa->fetchRow($dadosEstimativas)->toArray();
        
        $dadosGerais[0] = $dadosProj;
        $dadosGerais[1] = $dadosEspProj;
        $dadosGerais[2] = $dadosEspDesenvProj;
        $dadosGerais[3] = $dadosEstimativas;
        
        return $dadosGerais;
    }

    public function selectProjetoPdfBd ($idProj)
    {
        $dadosGerais = array ();
        
        $dbProj = new Application_Model_DbTable_ProjetosBd();
        
        $dbEstimativa = new Application_Model_DbTable_EstimativasBd();
        
        $dadosProj = $dbProj->select()->from($dbProj)->where('idProjetoBd ='.$idProj);
        
        $dadosProj = $dbProj->fetchRow($dadosProj)->toArray();
        
        $dadosEstimativas = $dbEstimativa->select()->from($dbEstimativa)->where('codProjBd ='.$idProj);
        
        $dadosEstimativas = $dbEstimativa->fetchRow($dadosEstimativas)->toArray();
        
        $dadosGerais[0] = $dadosProj;
        $dadosGerais[1] = $dadosEstimativas;
        
        return $dadosGerais;
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
        
        $idProj = $dbProj->select()->from($dbProj)->where('codCliente = ?', $codCliente['idCliente'])
                ->where('nome = ?', $request['tNomeProjeto']);
        
        $idProj = $dbProj->fetchRow($idProj);
        
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
        
        if (empty($request['tExtraCusto']))
            $request['tExtraCusto'] = "NF";
        if (empty($request['tExtraPF']))
            $request['tExtraPF'] = "NF";
        if (empty($request['tExtraProdutividade']))
            $request['tExtraProdutividade'] = "NF";
        
        $dadosEspProj = array 
        (
            'custoUsuario' => $request['tExtraCusto'],
            'pfUsuario' => $request['tExtraPF'],
            'prodUsuario' => $request['tExtraProdutividade'],
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
        
        if (is_null($idProj))
        {
            $codEspDesenvProj = $dbEspDesenvProj->insert($dadosEspDesenvProj);

            $codEspProj = $dbEspProj->insert($dadosEspProj);

            $dadosProj = array 
            (
                'nome' => $request['tNomeProjeto'],
                'descricao' => $request['tDescrProj'],
                'dataInicio' => $request['tDataIni'],
                'dataFim' => $request['tDataFim'],
                'tipo_projeto' => $request['tRadio'],
                'codCliente' => $codCliente['idCliente'],
                'excluido' => 'N',
                'codEspProj' => $codEspProj,
                'codEspDesenvProj' => $codEspDesenvProj
            );

            $codProj = $dbProj->insert($dadosProj);

            /**
             * Cálculo das estimativas
             */
            
            if ($request['tExtraCusto'] >= 1)
                $custo = $request['tExtraCusto'];
            
            else
            {
                $custo = (($request['tHoraNivel5']*$request['tValorNivel5']*$request['tDesenNivel5']*8)
                        + ($request['tHoraNivel4']*$request['tValorNivel4']*$request['tDesenNivel4']*8)
                        + ($request['tHoraNivel3']*$request['tValorNivel3']*$request['tDesenNivel3']*8)
                        + ($request['tHoraNivel2']*$request['tValorNivel2']*$request['tDesenNivel2']*8)
                        + ($request['tHoraNivel1']*$request['tValorNivel1']*$request['tDesenNivel1']*8)
                        + $request['tOutrosCustos']);
            }
            
            if ($request['tExtraProdutividade'] >= 1)
                $produtividade = $request['tExtraProdutividade'];
            
            else
            {
                
                $produtividade = (($request['tDesenNivel1']*5)+($request['tDesenNivel1']*4)
                                + ($request['tDesenNivel1']*3)+($request['tDesenNivel1']*2)
                                + ($request['tDesenNivel1']*1));
                 
                if ($produtividade == 0)
                {
                    
                    $esforco = 'Imp. Calcular';
                    $prazo = 'Imp. Calcular';
                    
                    $dadosEstimativas = array ('custo' => $custo, 'tempo' => $prazo, 'esforco' => $esforco, 'codProjeto' => $codProj, 'tipo_metrica' => 'AS');
                    
                    return $dbEstimativa->insert($dadosEstimativas);
                }
            }
            
            if ($request['tExtraPF'] >= 1)
                $pontosFuncao = $request['tExtraPF'];
            
            else
            {
                $pontosFuncao = (($request['tNumCasoUso']*7)+($request['tFluxoCasoUso']*10)+($request['tLinhaCasoUso']*15)
                                +($request['tNumInterface']*5)+($request['tNumSubInterface']*7)+($request['tNumElemInterface']*10)
                                +($request['tNumModUsers']*4)+($request['tNumSaidas']*4)+($request['tNumGeradoresResult']*5));
                
                if ($pontosFuncao == 0)
                {
                    $esforco = 'Imp. Estimar';
                    $prazo = 'Imp. Estimar';
                    
                    $dadosEstimativas = array ('custo' => $custo, 'tempo' => $prazo, 'esforco' => $esforco, 'codProjeto' => $codProj, 'tipo_metrica' => 'AS');
                    
                    return $dbEstimativa->insert($dadosEstimativas);
                }
            }
                
            $esforco = $pontosFuncao/$produtividade;

            $prazo = ($esforco*3)/($produtividade*5);
            
            $dadosEstimativas = array ('custo' => $custo, 'tempo' => $prazo, 'esforco' => $esforco, 'codProjeto' => $codProj, 'tipo_metrica' => 'AS');
            
            return $dbEstimativa->insert($dadosEstimativas);
        }
        else
        {
            $dadosProj = $dbProj->select()->from($dbProj,array('idProjetos'))->where("nome = ?",$request['tNomeProjeto']);
            $dadosProj = $dbProj->fetchRow($dadosProj)->toArray();
            
            $id = $dadosProj['idProjetos'];
            
            $dadosProjAll = $dbProj->select()->from($dbProj)->where("idProjetos = ?", $id);
            $dadosProjAll = $dbProj->fetchRow($dadosProjAll)->toArray();
        
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
        
        if (empty($request['tExtraCusto']))
            $request['tExtraCusto'] = "NF";
        if (empty($request['tExtraPF']))
            $request['tExtraPF'] = "NF";
        if (empty($request['tExtraProdutividade']))
            $request['tExtraProdutividade'] = "NF";
        
        $dadosEspProj = array 
        (
            'custoUsuario' => $request['tExtraCusto'],
            'pfUsuario' => $request['tExtraPF'],
            'prodUsuario' => $request['tExtraProdutividade'],
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
            'excluido' => 'N',
            'codCliente' => $dadosProjAll['codCliente'],
            'codEspProj' => $dadosProjAll['codEspProj'],
            'codEspDesenvProj' => $dadosProjAll['codEspDesenvProj']
        );
        
        $whereProj = $dbProj->getAdapter()->quoteInto("idProjetos = ?",$id);
        
        $dbProj->update($dadosProj, $whereProj);
        
        /**
         * Cálculo das estimativas
         */
        
        $select = $dbEstimativa->select()->from($dbEstimativa)->where('codProjeto = ?', $id);
        
        $select = $dbEstimativa->fetchRow($select)->toArray();
        
        if ($request['tExtraCusto'] >= 1)
                $custo = $request['tExtraCusto'];
            
            else
            {
                $custo = (($request['tHoraNivel5']*$request['tValorNivel5']*$request['tDesenNivel5']*8)
                        + ($request['tHoraNivel4']*$request['tValorNivel4']*$request['tDesenNivel4']*8)
                        + ($request['tHoraNivel3']*$request['tValorNivel3']*$request['tDesenNivel3']*8)
                        + ($request['tHoraNivel2']*$request['tValorNivel2']*$request['tDesenNivel2']*8)
                        + ($request['tHoraNivel1']*$request['tValorNivel1']*$request['tDesenNivel1']*8)
                        + $request['tOutrosCustos']);
            }
            
            if ($request['tExtraProdutividade'] >= 1)
                $produtividade = $request['tExtraProdutividade'];
            
            else
            {
                
                $produtividade = (($request['tDesenNivel1']*5)+($request['tDesenNivel1']*4)
                                + ($request['tDesenNivel1']*3)+($request['tDesenNivel1']*2)
                                + ($request['tDesenNivel1']*1));
                 
                if ($produtividade == 0)
                {
                    
                    $esforco = 'Imp. Calcular';
                    $prazo = 'Imp. Calcular';
                    
                    $dadosEstimativas = array ('custo' => $custo, 'tempo' => $prazo, 'esforco' => $esforco, 'codProjeto' => $id, 'tipo_metrica' => 'AS');
        
                    $whereEstimativa = $dbEstimativa->getAdapter()->quoteInto('idEstimativas = ?', $select['idEstimativas']);

                    return $dbEstimativa->update($dadosEstimativas, $whereEstimativa);
                }
            }
            
            if ($request['tExtraPF'] >= 1)
                $pontosFuncao = $request['tExtraPF'];
            
            else
            {
                $pontosFuncao = (($request['tNumCasoUso']*7)+($request['tFluxoCasoUso']*10)+($request['tLinhaCasoUso']*15)
                                +($request['tNumInterface']*5)+($request['tNumSubInterface']*7)+($request['tNumElemInterface']*10)
                                +($request['tNumModUsers']*4)+($request['tNumSaidas']*4)+($request['tNumGeradoresResult']*5));
                
                if ($pontosFuncao == 0)
                {
                    $esforco = 'Imp. Estimar';
                    $prazo = 'Imp. Estimar';
                    
                    $dadosEstimativas = array ('custo' => $custo, 'tempo' => $prazo, 'esforco' => $esforco, 'codProjeto' => $id, 'tipo_metrica' => 'AS');
        
                    $whereEstimativa = $dbEstimativa->getAdapter()->quoteInto('idEstimativas = ?', $select['idEstimativas']);

                    return $dbEstimativa->update($dadosEstimativas, $whereEstimativa);
                }
            }
                
            $esforco = $pontosFuncao/$produtividade;

            $prazo = ($esforco*3)/($produtividade*5);
        
            $dadosEstimativas = array ('custo' => $custo, 'tempo' => $prazo, 'esforco' => $esforco, 'codProjeto' => $id, 'tipo_metrica' => 'AS');

            $whereEstimativa = $dbEstimativa->getAdapter()->quoteInto('idEstimativas = ?', $select['idEstimativas']);

            return $dbEstimativa->update($dadosEstimativas, $whereEstimativa);
            
        }
    }
    
    public function updateProjeto (array $request)
    {
        
        $dbEspDesenvProj = new Application_Model_DbTable_EspecifDesenProj();
        $dbEspProj = new Application_Model_DbTable_EspecifProjetos();
        $dbProj = new Application_Model_DbTable_Projetos();
        $dbEstimativa = new Application_Model_DbTable_Estimativas();
        
        $id = $request['tHiddenProj'];
        
        $dadosProjAll = $dbProj->select()->from($dbProj)->where("idProjetos = ?", $id);
        $dadosProjAll = $dbProj->fetchRow($dadosProjAll)->toArray();
        
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
        
        if (empty($request['tExtraCusto']))
            $request['tExtraCusto'] = "NF";
        if (empty($request['tExtraPF']))
            $request['tExtraPF'] = "NF";
        if (empty($request['tExtraProdutividade']))
            $request['tExtraProdutividade'] = "NF";
        
        $dadosEspProj = array 
        (
            'custoUsuario' => $request['tExtraCusto'],
            'pfUsuario' => $request['tExtraPF'],
            'prodUsuario' => $request['tExtraProdutividade'],
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
            'excluido' => 'N',
            'codCliente' => $dadosProjAll['codCliente'],
            'codEspProj' => $dadosProjAll['codEspProj'],
            'codEspDesenvProj' => $dadosProjAll['codEspDesenvProj']
        );
        
        $whereProj = $dbProj->getAdapter()->quoteInto("idProjetos = ?",$id);
        
        $dbProj->update($dadosProj, $whereProj);
        
        /**
         * Cálculo das estimativas
         */
        
        $select = $dbEstimativa->select()->from($dbEstimativa)->where('codProjeto = ?', $id);
        
        $select = $dbEstimativa->fetchRow($select)->toArray();
        
        if ($request['tExtraCusto'] >= 1)
                $custo = $request['tExtraCusto'];
            
            else
            {
                $custo = (($request['tHoraNivel5']*$request['tValorNivel5']*$request['tDesenNivel5']*8)
                        + ($request['tHoraNivel4']*$request['tValorNivel4']*$request['tDesenNivel4']*8)
                        + ($request['tHoraNivel3']*$request['tValorNivel3']*$request['tDesenNivel3']*8)
                        + ($request['tHoraNivel2']*$request['tValorNivel2']*$request['tDesenNivel2']*8)
                        + ($request['tHoraNivel1']*$request['tValorNivel1']*$request['tDesenNivel1']*8)
                        + $request['tOutrosCustos']);
            }
            
            if ($request['tExtraProdutividade'] >= 1)
                $produtividade = $request['tExtraProdutividade'];
            
            else
            {
                
                $produtividade = (($request['tDesenNivel1']*5)+($request['tDesenNivel1']*4)
                                + ($request['tDesenNivel1']*3)+($request['tDesenNivel1']*2)
                                + ($request['tDesenNivel1']*1));
                 
                if ($produtividade == 0)
                {
                    
                    $esforco = 'Imp. Calcular';
                    $prazo = 'Imp. Calcular';
                    
                    $dadosEstimativas = array ('custo' => $custo, 'tempo' => $prazo, 'esforco' => $esforco, 'codProjeto' => $id, 'tipo_metrica' => 'AS');
        
                    $whereEstimativa = $dbEstimativa->getAdapter()->quoteInto('idEstimativas = ?', $select['idEstimativas']);

                    return $dbEstimativa->update($dadosEstimativas, $whereEstimativa);
                }
            }
            
            if ($request['tExtraPF'] >= 1)
                $pontosFuncao = $request['tExtraPF'];
            
            else
            {
                $pontosFuncao = (($request['tNumCasoUso']*7)+($request['tFluxoCasoUso']*10)+($request['tLinhaCasoUso']*15)
                                +($request['tNumInterface']*5)+($request['tNumSubInterface']*7)+($request['tNumElemInterface']*10)
                                +($request['tNumModUsers']*4)+($request['tNumSaidas']*4)+($request['tNumGeradoresResult']*5));
                
                if ($pontosFuncao == 0)
                {
                    $esforco = 'Imp. Estimar';
                    $prazo = 'Imp. Estimar';
                    
                    $dadosEstimativas = array ('custo' => $custo, 'tempo' => $prazo, 'esforco' => $esforco, 'codProjeto' => $id, 'tipo_metrica' => 'AS');
        
                    $whereEstimativa = $dbEstimativa->getAdapter()->quoteInto('idEstimativas = ?', $select['idEstimativas']);

                    return $dbEstimativa->update($dadosEstimativas, $whereEstimativa);
                }
            }
                
            $esforco = $pontosFuncao/$produtividade;

            $prazo = ($esforco*3)/($produtividade*5);
        
            $dadosEstimativas = array ('custo' => $custo, 'tempo' => $prazo, 'esforco' => $esforco, 'codProjeto' => $id, 'tipo_metrica' => 'AS');

            $whereEstimativa = $dbEstimativa->getAdapter()->quoteInto('idEstimativas = ?', $select['idEstimativas']);

            return $dbEstimativa->update($dadosEstimativas, $whereEstimativa);
    }
    
    public function deleteProjeto ($idProj)
    {
        $db = new Application_Model_DbTable_Projetos();
        
        $dados = $db->select()->from($db, array('excluido'))->where('idProjetos = ?', $idProj);
        
        $dados = $db->fetchRow($dados)->toArray();
        
        $dadosProj = array 
        (
            'excluido' => 'S',
        );
        
        $where = $db->getAdapter()->quoteInto("idProjetos = ?", $idProj);
        $db->update($dadosProj, $where);
    }
    
    public function insertProjetoBD (array $request, $idUser)
    {
        $dbProjBd = new Application_Model_DbTable_ProjetosBd();
        $dbEstBd = new Application_Model_DbTable_EstimativasBd();
        $dbCliente = new Application_Model_DbTable_Cliente();
        
        $codCliente = $dbCliente->select()->from($dbCliente)->where('codLogin = ?', $idUser);
        
        $codCliente = $dbCliente->fetchRow($codCliente)->toArray();
        
        $quantTipoProj = $dbProjBd->select()->from($dbProjBd)
                ->where('tipo_projeto = ?', $request['tRadio']);
        
        $quantTipoProj = $dbProjBd->fetchRow($quantTipoProj)->toArray();
        
        $quantTipoProj = count($quantTipoProj['tipo_projeto']);
        
        $medias = $dbProjBd->select()->from($dbProjBd, array('avg(pf)','avg(custo)','avg(tempo)','avg(esforco)'))
                ->where('tipo_projeto = ?', $request['tRadio']);
        
        $medias = $dbProjBd->fetchRow($medias)->toArray();
        
        //var_dump($medias);die;
        
        if ((empty($request['tCusto'])) || ($request['tCusto'] == 0))
        {
            $request['tCusto'] = "0";
            
            $custo1 = ($request['tPF']*$medias['avg(custo)'])/$medias['avg(pf)'];
            
            $custo2 = ($request['tTempo']*$medias['avg(custo)'])/$medias['avg(tempo)'];
            
            $custo3 = ($request['tEsforco']*$medias['avg(custo)'])/$medias['avg(esforco)'];
            
            $custoFinal = ($custo1+$custo2+$custo3)/3;
            
            
        }
        
        else if (empty($request['tTempo']) || ($request['tTempo'] == 0))
        {
            $request['tTempo'] = "0";
            
            $tempo1 = ($request['tPF']*$medias['avg(tempo)'])/$medias['avg(pf)'];
            $tempo2 = ($request['tCusto']*$medias['avg(tempo)'])/$medias['avg(custo)'];
            $tempo3 = ($request['tEsforco']*$medias['avg(tempo)'])/$medias['avg(esforco)'];
            $tempoFinal = ($tempo1+$tempo2+$tempo3)/3;
            
        }
        
        else if (empty($request['tEsforco']) || ($request['tCusto'] == 0))
        {
            $request['tEsforco'] = "0";
            
            $esforco1 = ($request['tPF']*$medias['avg(esforco)'])/$medias['avg(pf)'];
            $esforco2 = ($request['tTempo']*$medias['avg(esforco)'])/$medias['avg(tempo)'];
            $esforco3 = ($request['tCusto']*$medias['avg(esforco)'])/$medias['avg(custo)'];
            $esforcoFinal = ($esforco1+$esforco2+$esforco3)/3;
            
        }
        
        $dados = array 
        (
            'tipo_projeto' => $request['tRadio'],
            'nome' => $request['tNome'],
            'pf' => $request['tPF'],
            'custo' => $request['tCusto'],
            'tempo' => $request['tTempo'],
            'esforco' => $request['tEsforco'],
            'excluido' => 'N',
            'descricao'=> $request['tDescrProj'],
            'dataIni' => $request['tDataIni'],
            'dataFim' => $request['tDataFim'],
            'codCliente' => $codCliente['idCliente']
        );
        
        $codProj = $dbProjBd->insert($dados);
        
        if($request['tCusto']>=1)
            $custoFinal = $request['tCusto'].'-'.'FPU';
        if ($request['tTempo']>=1)
            $tempoFinal = $request['tTempo'].'-'.'FPU';
        if ($request['tEsforco'])
            $esforcoFinal = $request['tEsforco'].'-'.'FPU';
        
        $dados = array 
        (
            'custo' => $custoFinal,
            'tempo' => $tempoFinal,
            'esforco' => $esforcoFinal,
            'tipo_metrica' => 'BD',
            'codProjBd' => $codProj
        );
        
        $dbEstBd->insert($dados);
    }
    
    public function selectProjetoBD ($idUser)
    {
        $db = new Application_Model_DbTable_ProjetosBd();
        
        $dbCliente = new Application_Model_DbTable_Cliente;
        
        $codCliente = $dbCliente->select()->from($dbCliente)->where('codLogin ='.$idUser);
        
        $codCliente = $dbCliente->fetchRow($codCliente)->toArray();
        
        $select = $db->select()->from($db)->order('nome')->where("codCliente =".$codCliente['idCliente'])
                ->where('excluido = ?', 'N');
        
        return $db->fetchAll($select)->toArray();
    }
    
    public function selectProjetoBD2 ($idProj)
    {
        $db = new Application_Model_DbTable_ProjetosBd();
        
        $select = $db->select()->from($db)->where("idProjetoBd = ?", $idProj)
                ->where('excluido = ?', 'N');
        
        return $db->fetchRow($select)->toArray();
    }
    
    public function updateBD (array $request)
    {
        $dbProjBd = new Application_Model_DbTable_ProjetosBd();
        $dbEstBd = new Application_Model_DbTable_EstimativasBd();
        
        $codCliente = $dbProjBd->select()->from($dbProjBd)->where('idProjetoBd = ?', $request['tHiddenProj']);
        $codCliente = $dbProjBd->fetchRow($codCliente)->toArray();
        
        $codEst = $dbEstBd->select()->from($dbEstBd)->where('codProjBd = ?', $request['tHiddenProj']);
        $codEst = $dbEstBd->fetchRow($codEst)->toArray();
        
        $quantTipoProj = $dbProjBd->select()->from($dbProjBd)
                ->where('tipo_projeto = ?', $request['tRadio']);
        
        $quantTipoProj = $dbProjBd->fetchRow($quantTipoProj)->toArray();
        
        $quantTipoProj = count($quantTipoProj['tipo_projeto']);
        
        $medias = $dbProjBd->select()->from($dbProjBd, array('avg(pf)','avg(custo)','avg(tempo)','avg(esforco)'))
                ->where('tipo_projeto = ?', $request['tRadio']);
        
        $medias = $dbProjBd->fetchRow($medias)->toArray();
        
        //var_dump($medias);die;
        
        if ((empty($request['tCusto'])) || ($request['tCusto'] == 0))
        {
            $request['tCusto'] = "0";
            
            $custo1 = ($request['tPF']*$medias['avg(custo)'])/$medias['avg(pf)'];
            
            $custo2 = ($request['tTempo']*$medias['avg(custo)'])/$medias['avg(tempo)'];
            
            $custo3 = ($request['tEsforco']*$medias['avg(custo)'])/$medias['avg(esforco)'];
            
            $custoFinal = ($custo1+$custo2+$custo3)/3;
            
            
        }
        
        else if (empty($request['tTempo']) || ($request['tTempo'] == 0))
        {
            $request['tTempo'] = "0";
            
            $tempo1 = ($request['tPF']*$medias['avg(tempo)'])/$medias['avg(pf)'];
            $tempo2 = ($request['tCusto']*$medias['avg(tempo)'])/$medias['avg(custo)'];
            $tempo3 = ($request['tEsforco']*$medias['avg(tempo)'])/$medias['avg(esforco)'];
            $tempoFinal = ($tempo1+$tempo2+$tempo3)/3;
            
        }
        
        else if (empty($request['tEsforco']) || ($request['tCusto'] == 0))
        {
            $request['tEsforco'] = "0";
            
            $esforco1 = ($request['tPF']*$medias['avg(esforco)'])/$medias['avg(pf)'];
            $esforco2 = ($request['tTempo']*$medias['avg(esforco)'])/$medias['avg(tempo)'];
            $esforco3 = ($request['tCusto']*$medias['avg(esforco)'])/$medias['avg(custo)'];
            $esforcoFinal = ($esforco1+$esforco2+$esforco3)/3;
            
        }
        
        $dados = array 
        (
            'tipo_projeto' => $request['tRadio'],
            'nome' => $request['tNome'],
            'pf' => $request['tPF'],
            'custo' => $request['tCusto'],
            'tempo' => $request['tTempo'],
            'esforco' => $request['tEsforco'],
            'excluido' => 'N',
            'descricao'=> $request['tDescrProj'],
            'dataIni' => $request['tDataIni'],
            'dataFim' => $request['tDataFim'],
            'codCliente' => $codCliente['codCliente']
        );
        
        $whereProjBd= $dbProjBd->getAdapter()->quoteInto("idProjetoBd = ?",$request['tHiddenProj']);
        
        $dbProjBd->update($dados, $whereProjBd);
        
        if($request['tCusto']>=1)
            $custoFinal = $request['tCusto'].'-'.'FPU';
        if ($request['tTempo']>=1)
            $tempoFinal = $request['tTempo'].'-'.'FPU';
        if ($request['tEsforco'])
            $esforcoFinal = $request['tEsforco'].'-'.'FPU';
        
        $dados = array 
        (
            'custo' => $custoFinal,
            'tempo' => $tempoFinal,
            'esforco' => $esforcoFinal,
            'tipo_metrica' => 'BD',
            'codProjBd' => $request['tHiddenProj']
        );
        
        $whereEstBd = $dbEstBd->getAdapter()->quoteInto("idEstimativasBD = ?",$codEst['idEstimativasBD']);
        
        $dbEstBd->update($dados, $whereEstBd);
        
    }
    
    public function deleteBD ($idProj)
    {
        
        $dbProjBd = new Application_Model_DbTable_ProjetosBd();
        
        $request = $dbProjBd->select()->from($dbProjBd)->where('idProjetoBd = ?', $idProj);
        $request = $dbProjBd->fetchRow($request)->toArray();
        
        $dados = array 
        (
            'tipo_projeto' => $request['tipo_projeto'],
            'nome' => $request['nome'],
            'pf' => $request['pf'],
            'custo' => $request['custo'],
            'tempo' => $request['tempo'],
            'esforco' => $request['esforco'],
            'excluido' => 'S',
            'descricao'=> $request['descricao'],
            'dataIni' => $request['dataIni'],
            'dataFim' => $request['dataFim'],
            'codCliente' => $request['codCliente']
        );
        
        $whereProjBd= $dbProjBd->getAdapter()->quoteInto("idProjetoBd =".$idProj);
        
        $dbProjBd->update($dados, $whereProjBd);
    }
}