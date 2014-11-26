<?php

class Application_Model_Relatorios
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
    
    public function selectRelatorio ($idUser)
    {
        $dbCliente = new Application_Model_DbTable_Cliente();
        $dbProjeto = new Application_Model_DbTable_Projetos();
        
        $codCliente = $dbCliente->select()->from($dbCliente)->where('codLogin ='.$idUser);
        
        $codCliente = $dbCliente->fetchRow($codCliente)->toArray();
        
        $codProjeto = $dbProjeto->select()->from($dbProjeto)->order('nome')->where('codCliente= '.$codCliente['idCliente'])
                ->where('excluido = ?', 'N');
        
        return $dbProjeto->fetchAll($codProjeto)->toArray();
    }

    public function pdfRelatorio (array $request, $op)
    {   
        $primeiroNome = explode(" ", $request[0]['nome']);

        $pdf=new FPDF("P","mm","A4");
        $pdf->Open();
        
        $title = 'Relatório de Projeto';
        $pdf->SetTitle($title);
        $pdf->AddPage();
        
        // Dados Gerais
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(200,220,255);
        $pdf->Cell(0,6,"Dados Gerais",0,1,'L',true);
        $pdf->Ln(2);
        
        // Título do Projeto
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(255,255,255);
        $pdf->Cell(50,6,"Título do Projeto:",0,0,'L',true);
        
        // Título do Projeto
        $pdf->SetFont('Arial','',12);
        $pdf->SetFillColor(255,255,255);
        $pdf->Cell(13,6,$request[0]['nome'],0,1,'R',true);
        $pdf->Ln(2);
        
        // Tipo de Projeto
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(15,10,"Tipo de Projeto: ",0,0,'L');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(40,10,$request[0]['tipo_projeto'],0,0,'R');
        
        // Data início
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(35,10,"Início: ",0,0,'R');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(25,10,$request[0]['dataInicio'],0,0,'R');
        
        // Data fim
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(50,10,"Fim estimado: ",0,0,'R');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(25,10,$request[0]['dataFim'],0,0,'R');
        $pdf->Ln(12);
        
        // Descricao do Projeto
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(200,220,255);
        $pdf->Cell(0,6,"Descrição do Projeto",0,1,'L',true);
        
        // Descrição do Projeto
        $pdf->SetFont('Arial','',12);
        $pdf->SetFillColor(255,255,255);
        $pdf->MultiCell(0,10,trim($request[0]['descricao']),0,1,'L',true);
        $pdf->Ln(2);
        
        // Informações Desenvolvedores do Projeto
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(200,220,255);
        $pdf->Cell(0,6,"Informações Sobre Os Desenvolvedores do Projeto",0,1,'L',true);
        $pdf->Ln(4);
        
        $header = array('Desenv. Nível', 'Qt. Desenv', 'Qt. Hora', 'Valor Hora');
        
        $pdf->SetFont('Arial','',12);
        $pdf->SetFillColor(255,0,0);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128,0,0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B');
        
        // Header
        $w = array(47, 47, 47, 47);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
        
        // Color and font restoration
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        
        // Data
        $fill = false;
        
        //Nível 5
        $pdf->Cell($w[0],6,"5",'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[2]['col_nv_5'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[2]['quant_hora_nv5'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[2]['valor_hora_nv5'],'LR',0,'R',$fill);
        $pdf->Ln();
        
        $fill = !$fill;
        
        //Nível 4
        $pdf->Cell($w[0],6,"4",'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[2]['col_nv_4'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[2]['quant_hora_nv4'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[2]['valor_hora_nv4'],'LR',0,'R',$fill);
        $pdf->Ln();
        
        $fill = !$fill;
        
        //Nível 3
        $pdf->Cell($w[0],6,"3",'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[2]['col_nv_3'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[2]['quant_hora_nv3'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[2]['valor_hora_nv3'],'LR',0,'R',$fill);
        $pdf->Ln();
        
        $fill = !$fill;
        
        //Nível 2
        $pdf->Cell($w[0],6,"2",'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[2]['col_nv_2'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[2]['quant_hora_nv2'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[2]['valor_hora_nv2'],'LR',0,'R',$fill);
        $pdf->Ln();
        
        $fill = !$fill;
        
        //Nível 1
        $pdf->Cell($w[0],6,"1",'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[2]['col_nv_1'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[2]['quant_hora_nv1'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[2]['valor_hora_nv1'],'LR',0,'R',$fill);
        $pdf->Ln();
        
        $fill = !$fill;
        
        // Closing line
        $pdf->Cell(array_sum($w),0,'','T');
        
        $pdf->Ln(4);
        
        // Informações do Software
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(200,220,255);
        $pdf->Cell(0,6,"Informações do Software",0,1,'L',true);
        $pdf->Ln(4);
        
        //Dados do Usuário
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(255,255,255);
        $pdf->Cell(0,6,"Dados Gerais do Software",0,1,'L',true);
        $pdf->Ln(4);
        
        $header = array('Nº Modif. Usuários', 'Nº de Saídas', 'Nº Geradores Result', 'Outros Custos');
        
        $pdf->SetFont('Arial','',12);
        $pdf->SetFillColor(0,200,0);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128,0,0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B');
        
        // Header
        $w = array(47.1, 47.1, 47.1, 47.1);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
        
        // Color and font restoration
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        
        // Data
        $fill = false;
        
        // Dados da interface
        $pdf->Cell($w[0],6,$request[1]['num_mod_usuarios'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[1]['num_saidas'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[1]['num_geradores_result'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[1]['outros_custos'],'LR',0,'R',$fill);
        $pdf->Ln();
        
        $pdf->Cell(array_sum($w),0,'','T');
        
        $pdf->Ln(4);
        
        //Dados do Usuário
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(255,255,255);
        $pdf->Cell(0,6,"Dados dos Casos de Uso",0,1,'L',true);
        $pdf->Ln(4);
        
        $header = array('Nº Casos de Uso', 'Fluxos Casos de Uso', 'Linhas Casos de Uso');
        
        $pdf->SetFont('Arial','',12);
        $pdf->SetFillColor(0,0,220);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128,0,0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B');
        
        // Header
        $w = array(63, 63, 63);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
        
        // Color and font restoration
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        
        // Data
        $fill = false;
        
        // Dados dos casos de uso
        $pdf->Cell($w[0],6,$request[1]['num_casos_usos'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[1]['fluxos_casos_usos'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[1]['linhas_casos_uso'],'LR',0,'R',$fill);
        $pdf->Ln();
        
        $pdf->Cell(array_sum($w),0,'','T');
        
        $pdf->Ln(4);
        
        //Dados do Usuário
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(255,255,255);
        $pdf->Cell(0,6,"Dados da Interface",0,1,'L',true);
        $pdf->Ln(4);
        
        $header = array('Nº de Interfaces', 'Nº de Sub Interfaces', 'Nº de Elem. Interfaces');
        
        $pdf->SetFont('Arial','',12);
        $pdf->SetFillColor(200,0,0);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128,0,0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B');
        
        // Header
        $w = array(63, 63, 63);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
        
        // Color and font restoration
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        
        // Data
        $fill = false;
        
        // Dados da interface
        $pdf->Cell($w[0],6,$request[1]['num_interfaces'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[1]['num_sub_interfaces'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[1]['num_elem_interfaces'],'LR',0,'R',$fill);
        $pdf->Ln();
        
        $pdf->Cell(array_sum($w),0,'','T');
        
        $pdf->Ln(4);
        
        //Dados das estimativas
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(255,255,255);
        $pdf->Cell(0,6,"Dados das Estimativas",0,1,'L',true);
        $pdf->Ln(4);
        
        $header = array('Custo', 'Tempo', 'Esforço');
        
        $pdf->SetFont('Arial','',12);
        $pdf->SetFillColor(100,100,100);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128,0,0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B');
        
        // Header
        $w = array(63, 63, 63);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
        
        // Color and font restoration
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        
        // Data
        $fill = false;
        
        // Dados da interface
        $pdf->Cell($w[0],6,$request[3]['tempo'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[3]['custo'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[3]['esforco'],'LR',0,'R',$fill);
        $pdf->Ln();
        
        $pdf->Cell(array_sum($w),0,'','T');
        
        $pdf->Ln(4);
        
        $pdf->Ln(4);
        
        $title = 'Relatório de Projeto';
        $pdf->SetTitle($title);
        $pdf->AddPage();
        
        // Descricao do Projeto
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(200,220,255);
        $pdf->Cell(0,6,"Métrica Utilizada",0,1,'L',true);
        
        // Descrição do Projeto
        $pdf->SetFont('Arial','',12);
        $pdf->SetFillColor(255,255,255);
        $pdf->MultiCell(0,10,trim("As métricas que não necessitam de banco, como o nome já diz, é uma métrica autossuficiente que só depende de certas informações que são necessárias de qualquer forma para se completar um projeto, para que com essas informações mais básicas, aquelas informações que são mais importantes sejam estimadas de maneira mais prática e mesmo que não extas, com uma margem de erro no mínimo possível.
Essa métrica por ser algo que tenta ser bem precisa, a primeira vista pode assustar pelo tanto de informação que ela pede, mas isso tudo é necessário para a melhor resolução dos problemas do usuário, como todas são informações que qualquer projeto tem que ter, nunca o usuário não terá essas informações, para que o programa esteja utilizável em qualquer oportunidade.
Dentre esses valores que tem que ser fornecidos, estáo número de funcionários e seus respectivos níveis e salários referentes às suas horas de trabalho, custos adicionais, vários valores técnicos referentes ao código, à interface entre outros para dai sim, encontrar os valores que o EPYON está disposto a facilitar a visualização das estimativas para os usuários, que são:
*	Custo
*	Tempo
*	Esforço
"),0,1,'L',true);
        $pdf->Ln(2);
        
        if (!$op)
            $pdf->Output('relatorio_'.$primeiroNome[0].'.pdf','I');
        else
            $pdf->Output('relatorio_'.$primeiroNome[0].'.pdf','D');
    }
    
    public function pdfRelatorioBd (array $request, $op)
    {   
        $primeiroNome = explode(" ", $request[0]['nome']);

        $pdf=new FPDF("P","mm","A4");
        $pdf->Open();
        
        $title = 'Relatório de Projeto';
        $pdf->SetTitle($title);
        $pdf->AddPage();
        
        // Dados Gerais
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(200,220,255);
        $pdf->Cell(0,6,"Dados Gerais",0,1,'L',true);
        $pdf->Ln(2);
        
        // Título do Projeto
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(255,255,255);
        $pdf->Cell(50,6,"Título do Projeto:",0,0,'L',true);
        
        // Título do Projeto
        $pdf->SetFont('Arial','',12);
        $pdf->SetFillColor(255,255,255);
        $pdf->Cell(13,6,$request[0]['nome'],0,1,'R',true);
        $pdf->Ln(2);
        
        // Tipo de Projeto
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(15,10,"Tipo de Projeto: ",0,0,'L');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(40,10,$request[0]['tipo_projeto'],0,0,'R');
        
        // Data início
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(35,10,"Início: ",0,0,'R');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(25,10,$request[0]['dataIni'],0,0,'R');
        
        // Data fim
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(50,10,"Fim estimado: ",0,0,'R');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(25,10,$request[0]['dataFim'],0,0,'R');
        $pdf->Ln(12);
        
        // Descricao do Projeto
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(200,220,255);
        $pdf->Cell(0,6,"Descrição do Projeto",0,1,'L',true);
        
        // Descrição do Projeto
        $pdf->SetFont('Arial','',12);
        $pdf->SetFillColor(255,255,255);
        $pdf->MultiCell(0,10,trim($request[0]['descricao']),0,1,'L',true);
        $pdf->Ln(2);
        
        // Informações do Software
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(200,220,255);
        $pdf->Cell(0,6,"Informações do Software",0,1,'L',true);
        $pdf->Ln(4);
        
        // Descricao do Projeto
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(255,255,255);
        $pdf->Cell(0,6,"Dados fornecidos pelo usuário",0,1,'L',true);
        
        $pdf->Ln(4);
        
        $header = array('Custo', 'Tempo', 'Esforço');
        
        $pdf->SetFont('Arial','',12);
        $pdf->SetFillColor(0,200,0);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128,0,0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B');
        
        // Header
        $w = array(63, 63, 63);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
        
        // Color and font restoration
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        
        // Data
        $fill = false;
        
        // Dados da interface
        $pdf->Cell($w[0],6,$request[0]['tempo'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[0]['custo'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[0]['esforco'],'LR',0,'R',$fill);
        $pdf->Ln();
        
        $pdf->Cell(array_sum($w),0,'','T');
        
        $pdf->Ln(4);
        
        // Estimativas
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(255,255,255);
        $pdf->Cell(0,6,"Dados das Estimativas",0,1,'L',true);
        
        $pdf->Ln(4);
        
        $header = array('Custo', 'Tempo', 'Esforço');
        
        $pdf->SetFont('Arial','',12);
        $pdf->SetFillColor(100,100,100);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128,0,0);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('','B');
        
        // Header
        $w = array(63, 63, 63);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $pdf->Ln();
        
        // Color and font restoration
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        
        // Data
        $fill = false;
        
        // Dados da interface
        $pdf->Cell($w[0],6,$request[1]['tempo'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[1]['custo'],'LR',0,'R',$fill);
        $pdf->Cell($w[0],6,$request[1]['esforco'],'LR',0,'R',$fill);
        $pdf->Ln();
        
        $pdf->Cell(array_sum($w),0,'','T');
        
        $pdf->Ln(4);
        
        $title = 'Relatório de Projeto';
        $pdf->SetTitle($title);
        $pdf->AddPage();
        
        // Descricao do Projeto
        $pdf->SetFont('Arial','B',12);
        $pdf->SetFillColor(200,220,255);
        $pdf->Cell(0,6,"Métrica Utilizada",0,1,'L',true);
        
        // Descrição do Projeto
        $pdf->SetFont('Arial','',12);
        $pdf->SetFillColor(255,255,255);
        $pdf->MultiCell(0,10,trim("A métrica da Tabela do Banco de Dados é uma métrica utilizada para estimar o valor que o usuário está em busca a partir de dados existentes.
Então sem a necessidade de ter todo um aparato de informações para achar o resultado necessário, o usuário pode-se utilizar desta métrica da Tabela de Banco de Dados para encontrar um valor entre as seguintes opções:
*	Ponto de Função
*	Custo
*	Tempo
*	Esforço
Tudo que o usuário necessitará é de um Banco previamente inserido, o tipo do seu projeto, entre Desktop, Mobile ou Web e três entre os quatro valores citados acima, para achar o quarto.
Rapidamente o software informara à resolução que o usuário tanto procuraria, com inúmeros cálculos sem o calculo ágil do software.
"),0,1,'L',true);
        $pdf->Ln(2);
        
        if (!$op)
            $pdf->Output('relatorio_'.$primeiroNome[0].'.pdf','I');
        else
            $pdf->Output('relatorio_'.$primeiroNome[0].'.pdf','D');
    }
    
    public function downloadManual ()
    {
        $pdf=new FPDF("P","mm","A4");
        $pdf->Open();
        
        $pdf->Image('pag-colaboradores.png');
        
        $pdf->Output('manual_do_usuario.pdf','I');
    }
}

