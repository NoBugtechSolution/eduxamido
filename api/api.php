<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
define("API_KEY", "AP76Hf4Np13BAQS9371HB");
if (!isset($_GET['api_key']) || $_GET['api_key'] !== API_KEY) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Invalid API key"]);
    exit;
}
?>