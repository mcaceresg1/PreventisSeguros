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

// Determinar el tipo de cotización
$tipo_cotizacion = $_POST['tipo_cotizacion'] ?? 'vehicular';

// Validar campos requeridos básicos según el tipo
if ($tipo_cotizacion === 'vehicular') {
    if (empty($_POST['nombre']) || empty($_POST['telefono'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Nombre y teléfono son requeridos']);
        exit;
    }
} elseif ($tipo_cotizacion === 'hogar') {
    if (empty($_POST['tipoHogar']) || empty($_POST['valorHogar']) || empty($_POST['ubicacionHogar'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
        exit;
    }
} elseif ($tipo_cotizacion === 'salud') {
    if (empty($_POST['tipoSalud']) || empty($_POST['edadSalud']) || empty($_POST['coberturaSalud'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
        exit;
    }
} elseif ($tipo_cotizacion === 'sctr_vida_ley') {
    // Validación básica - se completará cuando se definan los campos
    // Por ahora solo validamos que el tipo esté presente
}

// Preparar email según el tipo de cotización
if ($tipo_cotizacion === 'vehicular') {
    // Sanitizar y obtener datos vehiculares
    $nombre = htmlspecialchars(trim($_POST['nombre']), ENT_QUOTES, 'UTF-8');
    $tipoDoc = htmlspecialchars(trim($_POST['tipoDoc'] ?? ''), ENT_QUOTES, 'UTF-8');
    $documento = htmlspecialchars(trim($_POST['documento'] ?? ''), ENT_QUOTES, 'UTF-8');
    $telefono = htmlspecialchars(trim($_POST['telefono']), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars(trim($_POST['email'] ?? ''), ENT_QUOTES, 'UTF-8');
    $uso = htmlspecialchars(trim($_POST['uso'] ?? ''), ENT_QUOTES, 'UTF-8');
    $marca = htmlspecialchars(trim($_POST['marca'] ?? ''), ENT_QUOTES, 'UTF-8');
    $modelo = htmlspecialchars(trim($_POST['modelo'] ?? ''), ENT_QUOTES, 'UTF-8');
    $ciudad = htmlspecialchars(trim($_POST['ciudad'] ?? ''), ENT_QUOTES, 'UTF-8');
    $anio = htmlspecialchars(trim($_POST['anio'] ?? ''), ENT_QUOTES, 'UTF-8');
    $combustible = htmlspecialchars(trim($_POST['combustible'] ?? ''), ENT_QUOTES, 'UTF-8');
    $companias = isset($_POST['companias']) ? $_POST['companias'] : [];
    $nombreOtra = htmlspecialchars(trim($_POST['nombreOtra'] ?? ''), ENT_QUOTES, 'UTF-8');
    $departamento = htmlspecialchars(trim($_POST['departamento'] ?? ''), ENT_QUOTES, 'UTF-8');
    
    $email_subject = "Nueva Cotización de Seguro Vehicular - " . $nombre;
    $email_body = "Se ha recibido una nueva solicitud de cotización de seguro vehicular.\n\n";
    $email_body .= "=== DATOS DEL CLIENTE ===\n";
    $email_body .= "Nombre: " . $nombre . "\n";
    $email_body .= "Tipo de Documento: " . $tipoDoc . "\n";
    $email_body .= "Número de Documento: " . $documento . "\n";
    $email_body .= "Teléfono: " . $telefono . "\n";
    if (!empty($email)) {
        $email_body .= "Email: " . $email . "\n";
    }
    $email_body .= "\n=== DATOS DEL VEHÍCULO ===\n";
    $email_body .= "Uso: " . $uso . "\n";
    $email_body .= "Marca: " . $marca . "\n";
    $email_body .= "Modelo: " . $modelo . "\n";
    $email_body .= "Año: " . $anio . "\n";
    $email_body .= "Combustible: " . $combustible . "\n";
    $email_body .= "Ciudad: " . $ciudad . "\n";
    if (!empty($departamento)) {
        $email_body .= "Departamento: " . $departamento . "\n";
    }
    $email_body .= "\n=== COMPAÑÍAS DE SEGUROS PREFERIDAS ===\n";
    if (!empty($companias) && is_array($companias)) {
        foreach ($companias as $compania) {
            $email_body .= "- " . htmlspecialchars($compania, ENT_QUOTES, 'UTF-8') . "\n";
        }
    }
    if (!empty($nombreOtra)) {
        $email_body .= "- Otra: " . $nombreOtra . "\n";
    }
    $reply_to_email = (!empty($email) ? $email : $to_email);
    $reply_to_name = $nombre;
    
} elseif ($tipo_cotizacion === 'hogar') {
    // Sanitizar y obtener datos de hogar
    $tipoHogar = htmlspecialchars(trim($_POST['tipoHogar']), ENT_QUOTES, 'UTF-8');
    $valorHogar = htmlspecialchars(trim($_POST['valorHogar']), ENT_QUOTES, 'UTF-8');
    $ubicacionHogar = htmlspecialchars(trim($_POST['ubicacionHogar']), ENT_QUOTES, 'UTF-8');
    $emailHogar = htmlspecialchars(trim($_POST['emailHogar'] ?? ''), ENT_QUOTES, 'UTF-8');
    $nombreHogar = htmlspecialchars(trim($_POST['nombreHogar'] ?? ''), ENT_QUOTES, 'UTF-8');
    $telefonoHogar = htmlspecialchars(trim($_POST['telefonoHogar'] ?? ''), ENT_QUOTES, 'UTF-8');
    
    $email_subject = "Nueva Cotización de Seguro de Hogar";
    $email_body = "Se ha recibido una nueva solicitud de cotización de seguro de hogar.\n\n";
    $email_body .= "=== DATOS DEL CLIENTE ===\n";
    if (!empty($nombreHogar)) {
        $email_body .= "Nombre: " . $nombreHogar . "\n";
    }
    if (!empty($telefonoHogar)) {
        $email_body .= "Teléfono: " . $telefonoHogar . "\n";
    }
    if (!empty($emailHogar)) {
        $email_body .= "Email: " . $emailHogar . "\n";
    }
    $email_body .= "\n=== DATOS DE LA VIVIENDA ===\n";
    $email_body .= "Tipo de vivienda: " . $tipoHogar . "\n";
    $email_body .= "Valor de la vivienda: S/ " . $valorHogar . "\n";
    $email_body .= "Ubicación: " . $ubicacionHogar . "\n";
    
    $reply_to_email = (!empty($emailHogar) ? $emailHogar : $to_email);
    $reply_to_name = !empty($nombreHogar) ? $nombreHogar : 'Cliente';
    
} elseif ($tipo_cotizacion === 'salud') {
    // Sanitizar y obtener datos de salud
    $tipoSalud = htmlspecialchars(trim($_POST['tipoSalud']), ENT_QUOTES, 'UTF-8');
    $edadSalud = htmlspecialchars(trim($_POST['edadSalud']), ENT_QUOTES, 'UTF-8');
    $coberturaSalud = htmlspecialchars(trim($_POST['coberturaSalud']), ENT_QUOTES, 'UTF-8');
    $emailSalud = htmlspecialchars(trim($_POST['emailSalud'] ?? ''), ENT_QUOTES, 'UTF-8');
    $nombreSalud = htmlspecialchars(trim($_POST['nombreSalud'] ?? ''), ENT_QUOTES, 'UTF-8');
    $telefonoSalud = htmlspecialchars(trim($_POST['telefonoSalud'] ?? ''), ENT_QUOTES, 'UTF-8');
    
    $email_subject = "Nueva Cotización de Seguro de Salud";
    $email_body = "Se ha recibido una nueva solicitud de cotización de seguro de salud.\n\n";
    $email_body .= "=== DATOS DEL CLIENTE ===\n";
    if (!empty($nombreSalud)) {
        $email_body .= "Nombre: " . $nombreSalud . "\n";
    }
    if (!empty($telefonoSalud)) {
        $email_body .= "Teléfono: " . $telefonoSalud . "\n";
    }
    if (!empty($emailSalud)) {
        $email_body .= "Email: " . $emailSalud . "\n";
    }
    $email_body .= "\n=== DATOS DEL PLAN ===\n";
    $email_body .= "Tipo de plan: " . $tipoSalud . "\n";
    $email_body .= "Edad del titular: " . $edadSalud . " años\n";
    $email_body .= "Cobertura: " . $coberturaSalud . "\n";
    
    $reply_to_email = (!empty($emailSalud) ? $emailSalud : $to_email);
    $reply_to_name = !empty($nombreSalud) ? $nombreSalud : 'Cliente';
    
} elseif ($tipo_cotizacion === 'sctr_vida_ley') {
    // Sanitizar y obtener datos de SCTR-Vida Ley
    // Los campos se agregarán cuando se definan
    $emailSctrVidaLey = htmlspecialchars(trim($_POST['emailSctrVidaLey'] ?? ''), ENT_QUOTES, 'UTF-8');
    $nombreSctrVidaLey = htmlspecialchars(trim($_POST['nombreSctrVidaLey'] ?? ''), ENT_QUOTES, 'UTF-8');
    $telefonoSctrVidaLey = htmlspecialchars(trim($_POST['telefonoSctrVidaLey'] ?? ''), ENT_QUOTES, 'UTF-8');
    
    $email_subject = "Nueva Cotización de Seguro SCTR-Vida Ley";
    $email_body = "Se ha recibido una nueva solicitud de cotización de seguro SCTR-Vida Ley.\n\n";
    $email_body .= "=== DATOS DEL CLIENTE ===\n";
    if (!empty($nombreSctrVidaLey)) {
        $email_body .= "Nombre: " . $nombreSctrVidaLey . "\n";
    }
    if (!empty($telefonoSctrVidaLey)) {
        $email_body .= "Teléfono: " . $telefonoSctrVidaLey . "\n";
    }
    if (!empty($emailSctrVidaLey)) {
        $email_body .= "Email: " . $emailSctrVidaLey . "\n";
    }
    $email_body .= "\n=== DATOS DE LA COTIZACIÓN ===\n";
    // Los campos específicos se agregarán cuando se definan
    $email_body .= "Formulario en construcción - Los campos se definirán próximamente.\n";
    
    $reply_to_email = (!empty($emailSctrVidaLey) ? $emailSctrVidaLey : $to_email);
    $reply_to_name = !empty($nombreSctrVidaLey) ? $nombreSctrVidaLey : 'Cliente';
    
} else {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Tipo de cotización no válido']);
    exit;
}

// Headers del email optimizados para SiteGround
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "From: " . $from_name . " <" . $from_email . ">\r\n";
$headers .= "Reply-To: " . $reply_to_name . " <" . $reply_to_email . ">\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "X-Priority: 3\r\n";

// Intentar enviar email
$mail_sent = @mail($to_email, $email_subject, $email_body, $headers);

if ($mail_sent) {
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Cotización enviada con éxito']);
} else {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error al enviar la cotización. Por favor, intente más tarde.']);
}
?>

