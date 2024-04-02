<?php

// Verificar que se esté recibiendo una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    exit("Method Not Allowed");
}

// Recibir los datos de la IPN
$status = isset($_POST['status']) ? $_POST['status'] : null;
$signature = isset($_POST['signature']) ? $_POST['signature'] : null;
$identifier = isset($_POST['identifier']) ? $_POST['identifier'] : null;
$data = isset($_POST['data']) ? $_POST['data'] : null;

// Verificar que se hayan recibido todos los datos necesarios
if ($status === null || $signature === null || $identifier === null || $data === null) {
    http_response_code(400); // Solicitud incorrecta
    exit("Bad Request");
}

// Obtener los datos adicionales de la IPN
$data = json_decode($data, true);

// Definir tu clave secreta proporcionada por Remipago
$secret_key = 'jz6e0b5nyuxg9g59dy6lftgm1criwguslj70s1fzvrnpasrwjb';

// Calcular la firma HMAC usando la clave secreta y el identificador
$custom_key = $data['amount'] . $identifier;
$my_signature = strtoupper(hash_hmac('sha256', $custom_key, $secret_key));

// Verificar la autenticidad de la IPN
if ($signature !== $my_signature) {
    http_response_code(401); // No autorizado
    exit("Unauthorized");
}

// Verificar si el estado del pago es "success"
if ($status === "success") {
    // Procesar el pago y actualizar el estado en tu sistema
    $payment_id = $data['payment_id'];
    // Realizar las acciones necesarias, como actualizar la base de datos, enviar correos electrónicos, etc.
    // ...

    // Mostrar la página con la información del pago exitoso
    $identifier = $data['identifier'];
    $amount = $data['amount'];
    $currency = $data['currency'];
    $details = $data['details'];
    
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pago Exitoso</title>
    </head>
    <body>
        <h1>Pago Exitoso</h1>
        <p>Identifier: <?php echo $identifier; ?></p>
        <p>Amount: <?php echo $amount; ?></p>
        <p>Currency: <?php echo $currency; ?></p>
        <p>Details: <?php echo $details; ?></p>
        <p>Estado del Pago: Exitoso</p>
    </body>
    </html>

    <?php
    exit();
} else {
    // Si el estado del pago no es "success", manejar según sea necesario
    // ...

    // Responder a Remipago para confirmar la recepción de la IPN
    echo json_encode(["success" => true]);
}
?>
