<?php
// Configuración de email - Mover a variables de entorno en producción
$to_email = 'rpv.seguros@gmail.com';
$from_email = 'noreply@rpvseguros.com';
$from_name = 'RPV Seguros';

// Validar método de petición
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Validar campos requeridos
if (empty($_POST['name']) || 
    empty($_POST['email']) || 
    empty($_POST['phone']) || 
    empty($_POST['message'])) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
    exit;
}

// Validar formato de email
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Email inválido']);
    exit;
}

// Validar longitud de campos
$name = trim($_POST['name']);
$email_address = trim($_POST['email']);
$phone = trim($_POST['phone']);
$message = trim($_POST['message']);

if (strlen($name) > 100 || strlen($email_address) > 255 || 
    strlen($phone) > 20 || strlen($message) > 2000) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Uno o más campos exceden la longitud permitida']);
    exit;
}

// Sanitizar datos
$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$email_address = filter_var($email_address, FILTER_SANITIZE_EMAIL);
$phone = filter_var($phone, FILTER_SANITIZE_STRING);
$message = filter_var($message, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

// Escapar para HTML
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$email_address = htmlspecialchars($email_address, ENT_QUOTES, 'UTF-8');
$phone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

// Preparar email
$email_subject = "Formulario de contacto RPV-SEGUROS: " . $name;
$email_body = "Ha recibido un mensaje del formulario de contacto de RPV-SEGUROS.\n\n";
$email_body .= "Detalles:\n\n";
$email_body .= "Nombre: " . $name . "\n";
$email_body .= "Email: " . $email_address . "\n";
$email_body .= "Teléfono: " . $phone . "\n";
$email_body .= "Mensaje:\n" . $message . "\n";

// Headers del email correctamente formateados
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "From: " . $from_name . " <" . $from_email . ">\r\n";
$headers .= "Reply-To: " . $name . " <" . $email_address . ">\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

// Intentar enviar email
$mail_sent = @mail($to_email, $email_subject, $email_body, $headers);

if ($mail_sent) {
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Mensaje enviado con éxito']);
} else {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error al enviar el mensaje. Por favor, intente más tarde.']);
}
?>


