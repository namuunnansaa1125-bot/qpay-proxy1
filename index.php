<?php
header('Content-Type: application/json');

$path = $_GET['path'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$body = file_get_contents('php://input');

// Authorization header дамжуулах
$headers = [];
if (function_exists('getallheaders')) {
    foreach (getallheaders() as $key => $value) {
        if (strtolower($key) === 'authorization') {
            $headers[] = "Authorization: $value";
        }
    }
}
$headers[] = 'Content-Type: application/json';

$url = "https://merchant-sandbox.qpay.mn/v2/" . $path;

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST  => $method,
    CURLOPT_HTTPHEADER     => $headers,
    CURLOPT_POSTFIELDS     => $body,
    CURLOPT_TIMEOUT        => 30,
]);

$response = curl_exec($ch);

if ($response === false) {
    echo json_encode(['error' => curl_error($ch)]);
} else {
    echo $response;
}
curl_close($ch);
