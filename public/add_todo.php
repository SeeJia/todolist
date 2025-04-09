<?php

require '../vendor/autoload.php';  // Autoload Composer dependencies

// use Dotenv\Dotenv;

// $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
// $dotenv->load();

$apiUrl = 'https://pktthkcdxffsdlktlppm.supabase.co/rest/v1/todolist'; 
$apiKey = $_ENV['SUPABASE_API_KEY'];
$bearerToken = $_ENV['SUPABASE_BEARER_TOKEN'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取表单数据
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];
    $email = $_POST['email'];
    
    // 获取当前时间并设置为马来西亚时区
    $dateTime = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));
    $currentTimestamp = $dateTime->format('Y-m-d H:i:s'); // 格式化为字符串

    // Prepare data to be sent, including adjusted timestamps
    $data = json_encode([
        "title" => $title,
        "description" => $description,
        "status" => $status,
        "priority" => $priority,
        "email" => $email,
        "created_at" => $currentTimestamp, // 设置创建时间
        "updated_at" => $currentTimestamp, // 设置更新时间
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $apiKey,
        'Authorization: Bearer ' . $bearerToken,
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  // Send data

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    $todos = json_decode($response, true);

    header("Location: ../index.php"); 
    exit();
}
?>
