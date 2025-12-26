<?php
// Configuración de email para SiteGround
$to_email = 'rpv.seguros@gmail.com';
// Usar el dominio del servidor si está disponible, de lo contrario usar el configurado
$server_domain = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'rpvseguros.com';
$from_email = 'noreply@' . $server_domain;
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
    empty($_POST['phone']) || 
    empty($_POST['ciudad']) || 
    empty($_POST['message'])) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
    exit;
}

// Validar longitud de campos
$name = trim($_POST['name']);
$phone = trim($_POST['phone']);
$ciudad = trim($_POST['ciudad']);
$message = trim($_POST['message']);

if (strlen($name) > 100 || strlen($phone) > 20 || 
    strlen($ciudad) > 100 || strlen($message) > 2000) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Uno o más campos exceden la longitud permitida']);
    exit;
}

// Sanitizar datos
$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$phone = filter_var($phone, FILTER_SANITIZE_STRING);
$ciudad = filter_var($ciudad, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$message = filter_var($message, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

// Escapar para HTML
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$phone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
$ciudad = htmlspecialchars($ciudad, ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

// Preparar email
$email_subject = "Formulario de contacto RPV-SEGUROS: " . $name;
$email_body = "Ha recibido un mensaje del formulario de contacto de RPV-SEGUROS.\n\n";
$email_body .= "Detalles:\n\n";
$email_body .= "Nombre: " . $name . "\n";
$email_body .= "Teléfono: " . $phone . "\n";
$email_body .= "Ciudad: " . $ciudad . "\n";
$email_body .= "Mensaje:\n" . $message . "\n";

// Headers del email optimizados para SiteGround
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "From: " . $from_name . " <" . $from_email . ">\r\n";
$headers .= "Reply-To: " . $to_email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "X-Priority: 3\r\n";

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


