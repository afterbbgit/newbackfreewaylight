<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$email = trim($input['email'] ?? '');

if (empty($email)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Email is required"]);
    exit;
}

$whitelist = file('whitelist.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$email = strtolower($email);
$allowed = false;

foreach ($whitelist as $entry) {
    $entry = strtolower(trim($entry));
    if (strpos($entry, '*@') === 0) {
        $domain = substr($entry, 2);
        if (str_ends_with($email, '@' . $domain)) {
            $allowed = true;
            break;
        }
    } elseif ($email === $entry) {
        $allowed = true;
        break;
    }
}

if ($allowed) {
    echo json_encode([
        "success" => true,
        "redirectUrl" => "https://yourdomain.com/proposal.pdf"
    ]);
} else {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Email not authorized"]);
}
