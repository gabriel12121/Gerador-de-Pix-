<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Defina seu Access Token do Mercado Pago
$access_token = "SEU_ACCESS_TOKEN"; 

// Captura o valor enviado pelo formulário
$valor = isset($_POST["valor"]) ? floatval($_POST["valor"]) : 0;

if ($valor <= 0) {
    echo json_encode(["erro" => "Valor inválido"]);
    exit;
}

// Dados do pagamento
$data = [
    "transaction_amount" => $valor,
    "description" => "Pagamento via Pix",
    "payment_method_id" => "pix",
    "payer" => [
        "email" => "cliente@email.com",
        "first_name" => "Cliente",
        "last_name" => "Teste",
    ]
];

// Enviar requisição para a API do Mercado Pago
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/v1/payments");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $access_token"
]);

$response = curl_exec($ch);
curl_close($ch);

// Decodificar resposta JSON
$pix_data = json_decode($response, true);

// Retornar os dados para o JavaScript
if (isset($pix_data["point_of_interaction"]["transaction_data"]["qr_code_base64"])) {
    echo json_encode([
        "qr_code" => $pix_data["point_of_interaction"]["transaction_data"]["qr_code"],
        "qr_code_base64" => $pix_data["point_of_interaction"]["transaction_data"]["qr_code_base64"]
    ]);
} else {
    echo json_encode(["erro" => "Erro ao gerar Pix"]);
}

?>
