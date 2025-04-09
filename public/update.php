<?php

require '../vendor/autoload.php';  // Autoload Composer dependencies

// use Dotenv\Dotenv;

// $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
// $dotenv->load();

$apiUrl = 'https://pktthkcdxffsdlktlppm.supabase.co/rest/v1/todolist'; 
$apiKey = $_ENV['SUPABASE_API_KEY'];
$bearerToken = $_ENV['SUPABASE_BEARER_TOKEN'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];

    // 设置马来西亚时区并获取当前时间
    $dateTime = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));
    $updatedAt = $dateTime->format('Y-m-d H:i:s'); // 格式化为字符串

    $updateData = [
        'title' => $title,
        'description' => $description,
        'status' => $status,
        'priority' => $priority,
        'updated_at' => $updatedAt, // 更新字段
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl . '?id=eq.' . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH'); // 使用 PATCH 方法更新数据
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $apiKey,
        'Authorization: Bearer ' . $bearerToken,
        'Content-Type: application/json',
    ]);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($updateData));

    $response = curl_exec($ch);
    
    if ($response === false) {
        echo 'cURL Error: ' . curl_error($ch);
        curl_close($ch);
        exit; // 终止脚本
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch); // 关闭 cURL 句柄

    if ($httpCode === 204) {
        // 204 表示成功但无返回内容
        header('location: ../index.php');
        exit;
    } elseif ($httpCode >= 200 && $httpCode < 300) {
        $responseData = json_decode($response, true);
        // 进一步处理 $responseData
        if (isset($responseData['id'])) {
            header('location: ../index.php');
            exit;
        } else {
            echo 'Update failed. Response: ' . json_encode($responseData);
        }
    } else {
        // 处理其他错误
        echo 'Update failed with status code: ' . $httpCode;
        echo 'Response: ' . $response;
    }
}
?>
