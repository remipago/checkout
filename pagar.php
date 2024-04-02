<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si se enviaron datos por POST desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar los valores del formulario
    $currency = $_POST["currency"];
    $selected_amount = isset($_POST["selected-amount"]) ? $_POST["selected-amount"] : null;
    $custom_amount = isset($_POST["custom-amount"]) ? $_POST["custom-amount"] : null;
    $details = $_POST["details"];
    $customer_name = $_POST["customer_name"];
    $customer_email = $_POST["customer_email"];

    // Generar un identificador único
    $identifier = uniqid();

    // Verificar si se seleccionó un monto prefijado o si se ingresó manualmente
    if (!empty($selected_amount)) {
        // Si se seleccionó un monto prefijado, usar ese valor
        $amount = $selected_amount;
    } elseif (!empty($custom_amount) && is_numeric($custom_amount) && $custom_amount > 0) {
        // Si se ingresó un monto personalizado y es numérico y mayor que cero, usar ese valor
        $amount = $custom_amount;
    } else {
        // Si no se proporcionó un monto válido, mostrar un mensaje de error
        echo "Error: No se proporcionó un monto válido.";
        exit();
    }

    echo "Monto ingresado manualmente: " . $custom_amount . "<br>";

    // Datos para la solicitud de pago
    $parameters = [
        'identifier' => $identifier,
        'currency' => $currency,
        'amount' => $amount,
        'details' => $details,
        'ipn_url' => 'https://changalab.remipago.com/ipn.php',
        'cancel_url' => 'https://changalab.remipago.com',
        'success_url' => 'https://changalab.remipago.com/success_url.php?currency=' . urlencode($currency) . '&amount=' . urlencode($amount) . '&details=' . urlencode($details),
        'public_key' => 'PUT_YOUR_PUBLIC_KEY_HERE',
        'site_logo' => 'https://remipago.com/assets/images/logoIcon/logo.png',
        'checkout_theme' => 'dark',
        'customer_name' => 'Juan',
        'customer_email' => 'juan@mail.com',
    ];

    // Debug: Mostrar parámetros antes de enviar la solicitud a Remipago
    echo "Parámetros para la solicitud a Remipago:<br>";
    var_dump($parameters);
    echo "<br>";

    // URL de la API de Remipago para el modo de prueba
    $url = "https://remipago.com/payment/initiate";

    // Inicializar la sesión cURL
    $ch = curl_init();

    // Establecer las opciones de la sesión cURL
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters)); // Convertir array a formato de consulta
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Realizar la solicitud a la API de Remipago
    $result = curl_exec($ch);

    // Verificar si hay errores en la solicitud
    if ($result === false) {
        echo "Error al realizar la solicitud: " . curl_error($ch);
        exit();
    }

    // Decodificar la respuesta JSON
    $responseData = json_decode($result, true);

    // Verificar si hay errores en la decodificación
    if ($responseData === null) {
        echo "Error al decodificar la respuesta JSON";
        exit();
    }

    // Verificar si hay errores en la respuesta
    if (isset($responseData['error'])) {
        echo "Error en la respuesta de Remipago: " . $responseData['error'];
        exit();
    }

    // Redirigir al usuario a la URL de pago proporcionada por la API de Remipago
    if (isset($responseData['url'])) {
        // Redirigir al usuario a la URL de pago
        header('Location: ' . $responseData['url']);
        exit();
    } else {
        // Si la solicitud falla, mostrar un mensaje de error al usuario
        echo "Error al iniciar el pago. Por favor, inténtelo de nuevo más tarde.";
    }
} else {
    // Si no se recibieron datos por POST, mostrar un mensaje de error
    echo "Error: No se recibieron datos del formulario.";
}

?>
