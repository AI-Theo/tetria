<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$prenom  = htmlspecialchars(trim($_POST['prenom'] ?? ''));
$nom     = htmlspecialchars(trim($_POST['nom'] ?? ''));
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$secteur = htmlspecialchars(trim($_POST['secteur'] ?? ''));
$message = htmlspecialchars(trim($_POST['message'] ?? ''));

if (!$prenom || !$nom || !$email || !$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Champs requis manquants']);
    exit;
}

$to      = 'contact@tetria.fr';
$subject = "Nouveau contact Tetria — $prenom $nom";

$body = "Nouveau message reçu depuis tetria.fr\n";
$body .= "==========================================\n\n";
$body .= "Prénom   : $prenom\n";
$body .= "Nom      : $nom\n";
$body .= "Email    : $email\n";
$body .= "Secteur  : $secteur\n\n";
$body .= "Message :\n$message\n\n";
$body .= "==========================================\n";
$body .= "Envoyé le : " . date('d/m/Y à H:i') . "\n";

$headers  = "From: noreply@tetria.fr\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$sent = mail($to, $subject, $body, $headers);

if ($sent) {
    echo json_encode(['success' => true, 'message' => 'Message envoyé avec succès']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'envoi']);
}
?>