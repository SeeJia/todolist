<?php 

require '../vendor/autoload.php';  // Autoload Composer dependencies

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$apiUrl = 'https://ibbhnrhodkqgzndymooe.supabase.co/rest/v1/todolist'; 
$apiKey = $_ENV['SUPABASE_API_KEY'];
$bearerToken = $_ENV['SUPABASE_BEARER_TOKEN'];

$id = $_GET['id']; // 要删除的记录 ID
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl . '?id=eq.' . $id);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); // 使用 DELETE 方法删除数据
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'apikey: ' . $apiKey,
    'Authorization: Bearer ' . $bearerToken,
    'Content-Type: application/json',
]);

$response = curl_exec($ch);

if ($response === false) {
    echo 'Error: ' . curl_error($ch);
} else {
    header('location: ../index.php');
}

curl_close($ch);
