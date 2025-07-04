<?php
// Allow CORS for frontend
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Get POSTed JSON input
$input = json_decode(file_get_contents('php://input'), true);
$email = isset($input['email']) ? trim($input['email']) : '';

// Basic email format validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Adresse e‑mail invalide.'
    ]);
    exit;
}

// Load whitelist.txt
$whitelistPath = __DIR__ . '/whitelist.txt';

if (!file_exists($whitelistPath)) {
    echo json_encode([
        'success' => false,
        'message' => 'Fichier de liste blanche introuvable.'
    ]);
    exit;
}

$whitelist = file($whitelistPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Compare email (case-insensitive)
if (in_array(strtolower($email), array_map('strtolower', $whitelist))) {
    echo json_encode([
        'success' => true,
        'redirectUrl' => 'https://yourdomain.com/FA7658923‑07‑2025.xls'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Adresse e‑mail non autorisée.'
    ]);
}
