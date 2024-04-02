<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Exitoso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 500px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4CAF50;
            text-align: center;
        }

        p {
            margin-bottom: 10px;
        }

        ul {
            list-style: none;
            padding-left: 0;
        }

        li {
            margin-bottom: 5px;
        }

        @media (max-width: 600px) {
            .container {
                max-width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pago Exitoso</h1>
        <p>¡Gracias por tu pago! El pago ha sido procesado exitosamente.</p>
        <p>Detalles:</p>
        <ul>
            <li>Moneda: <?php echo isset($_GET['currency']) ? $_GET['currency'] : 'No disponible'; ?></li>
            <li>Monto: <?php echo isset($_GET['amount']) ? $_GET['amount'] : 'No disponible'; ?></li>
            <li>Detalles: <?php echo isset($_GET['details']) ? $_GET['details'] : 'No disponibles'; ?></li>
        </ul>
        <p>La información de tu pago la puedes encontrar en tu cuenta de RemiPago.</p>
    </div>
</body>
</html>
