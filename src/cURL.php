<?php

/**
 * @param string $estado
 * @param int $ramo
 */
function chamarLambda($estado = "", $ramo = null) {
    echo "\n";
    echo "Rodando Estado - {$estado}....";
    echo "\n";
    echo "Ramo - {$ramo}....";
    echo "\n";
    // URL base do seu Lambda
    $lambdaURL = 'https://2lt7sgynmcirtwgd2mj46hiboq0nachn.lambda-url.us-east-1.on.aws/';

    // Construir a URL completa com os parâmetros e a urlBase
    $url = $lambdaURL . '?estado=' . urlencode($estado) . '&ramo=' . urlencode($ramo);

    // Inicializar cURL session com a url montada
    $ch = curl_init($url);
    
    // Configurar as opções da requisição cURL para retornar uma reposta string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Executar a requisição cURL
    $response = curl_exec($ch);

    // Verificar se ocorreu algum erro durante a requisição
    if (curl_errno($ch)) {
        return "Erro na requisição cURL: " . curl_error($ch) . "Ramo - {$ramo} do Estado {$estado}.";
    }

    // Fechar a sessão cURL
    curl_close($ch);

    echo "\n";
    echo "Link executado - ".$lambdaURL;
    echo "\n";

    return $response;
}


?>
