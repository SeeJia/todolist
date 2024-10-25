<?php

require '../vendor/autoload.php';  // Autoload Composer dependencies

// use Dotenv\Dotenv;

// // Load the .env file
// $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
// $dotenv->load();

session_start(); // 开始会话

$apiUrl = 'https://ibbhnrhodkqgzndymooe.supabase.co/rest/v1/todolist_user?select=*'; 
$apiKey = $_ENV['SUPABASE_API_KEY'];
$bearerToken = $_ENV['SUPABASE_BEARER_TOKEN'];

// 获取用户提交的表单数据
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

// 初始化 cURL
$ch = curl_init();

// 设置 cURL 选项
curl_setopt($ch, CURLOPT_URL, $apiUrl . '&email=eq.' . $email);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'apikey: ' . $apiKey,
    'Authorization: Bearer ' . $bearerToken,
    'Content-Type: application/json',
]);

// 执行请求
$response = curl_exec($ch);

// 关闭 cURL 会话
curl_close($ch);

$users = json_decode($response, true);
 // 检查用户是否存在
 if (!empty($users)) {
    // 假设数据库中密码存储为明文
    $storedPassword = $users[0]['password']; // 确保从数据库中正确提取密码
    $user_email = $users[0]['email']; 

    if (password_verify($password, $storedPassword)) {
        // 登录成功
        $_SESSION['email'] = $user_email; 
        header('Location: ../index.php');
        // 在这里，可以设置会话或其他处理
    } else {
        // 密码不正确
        echo "Invalid email or password.";
    }
} else {
    // 用户不存在
    echo "Invalid email or password.";
}
}

?>

