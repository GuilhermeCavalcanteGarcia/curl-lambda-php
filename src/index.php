<?php
include 'cURL.php';

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$estadosBrasileiros = array(
    // 'AC' => 'Acre',
    // 'AL' => 'Alagoas',
    // 'AP' => 'Amapá',
    // 'AM' => 'Amazonas',
    // 'BA' => 'Bahia',
    // 'CE' => 'Ceará',
    'DF' => 'Distrito Federal'
    // 'ES' => 'Espírito Santo',
    // 'GO' => 'Goiás',
    // 'MA' => 'Maranhão',
    // 'MT' => 'Mato Grosso',
    // 'MS' => 'Mato Grosso do Sul',
    // 'PA' => 'Pará',
    // 'PB' => 'Paraíba',
    // 'PR' => 'Paraná',
    // 'PE' => 'Pernambuco',
    // 'PI' => 'Piauí',
    // 'RJ' => 'Rio de Janeiro',
    // 'RN' => 'Rio Grande do Norte',
    // 'RS' => 'Rio Grande do Sul',
    // 'RO' => 'Rondônia',
    // 'RR' => 'Roraima',
    // 'SC' => 'Santa Catarina',
    // 'SP' => 'São Paulo',
    // 'SE' => 'Sergipe',
    // 'TO' => 'Tocantins'
);

foreach ($estadosBrasileiros as $uf => $value) {

    echo "\n";
    echo "Iniciando processo com {$uf} - {$value}...";
    echo "\n";

    for ($i=15; $i <= 24; $i++) {
        $inicio = microtime(true);

        echo "\n";
        echo "Rodando ramo[{$i}]/[{$uf}]/[{$value}] ...";
        echo "\n";
        
        $response = chamarLambda($uf, $i);
        
        $fim = microtime(true);  
        
        $tempoEmSegundos = round($fim - $inicio, 5);

        $tempoEmMinutos = $tempoEmSegundos / 60;

        $dataHoraAtual = date("Y-m-d H:i:s");

        if ($response == '"Processado com sucesso!"') {
            
            adicionarSucessoLinhaAoXLSX($uf, $i, $tempoEmMinutos, $tempoEmSegundos, $dataHoraAtual);

        } else {

            adicionarErroLinhaAoXLSX($uf, $i, $tempoEmMinutos, $tempoEmSegundos, $dataHoraAtual, $response);

        }
        
    }
    echo "\n";
    echo "Finalizado processo com {$uf}/{$value}...";
    echo "\n";
    echo "\n";
    echo "===========================================================";
    echo "\n";
}

function adicionarSucessoLinhaAoXLSX($estado, $ramo, $minutos, $segundos, $dataHoraAtual)
{
    $spreadsheet = IOFactory::load('../logs/sucessos.xlsx');

    $sheet = $spreadsheet->getActiveSheet();

    $proximaLinha = $sheet->getHighestRow() + 1;

    $sheet->setCellValue('A' . $proximaLinha, $estado);
    $sheet->setCellValue('B' . $proximaLinha, $ramo);
    $sheet->setCellValue('C' . $proximaLinha, $minutos);
    $sheet->setCellValue('D' . $proximaLinha, $segundos);
    $sheet->setCellValue('E' . $proximaLinha, $dataHoraAtual);

    $writer = new Xlsx($spreadsheet);
    $writer->save('../logs/sucessos.xlsx');

    echo "\n";
    echo "Adicionado log na planilha de SUCESSOS...";
    echo "\n";
}

function adicionarErroLinhaAoXLSX($estado, $ramo, $minutos, $segundos, $dataHoraAtual, $erro)
{
    $spreadsheet = IOFactory::load('../logs/erros.xlsx');

    $sheet = $spreadsheet->getActiveSheet();

    $proximaLinha = $sheet->getHighestRow() + 1;

    // Adicionar os dados à próxima linha
    $sheet->setCellValue('A' . $proximaLinha, $estado);
    $sheet->setCellValue('B' . $proximaLinha, $ramo);
    $sheet->setCellValue('C' . $proximaLinha, $minutos);
    $sheet->setCellValue('D' . $proximaLinha, $segundos);
    $sheet->setCellValue('E' . $proximaLinha, $dataHoraAtual);
    $sheet->setCellValue('F' . $proximaLinha, $erro);

    $writer = new Xlsx($spreadsheet);
    $writer->save('../logs/erros.xlsx');

    echo "\n";
    echo "Adicionado log na planilha de ERROS...";
    echo "\n";
}
?>
