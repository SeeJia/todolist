<?php
session_start(); // 开始会话

$apiUrl = 'https://ibbhnrhodkqgzndymooe.supabase.co/rest/v1/todolist?select=*'; 
$apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImliYmhucmhvZGtxZ3puZHltb29lIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MjkxNTA4ODQsImV4cCI6MjA0NDcyNjg4NH0.p-OQbxfMiHbegbW5-2YvflGtlCqU6NJ_NGJDyK6Ir_M';  
$bearerToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImliYmhucmhvZGtxZ3puZHltb29lIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MjkxNTA4ODQsImV4cCI6MjA0NDcyNjg4NH0.p-OQbxfMiHbegbW5-2YvflGtlCqU6NJ_NGJDyK6Ir_M';

// 获取用户提交的表单数据
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

// 初始化 cURL
$ch = curl_init();

// 设置 cURL 选项
curl_setopt($ch, CURLOPT_URL, $apiUrl);
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
    $userId = $users[0]['id']; 

    if ($password === $storedPassword) {
        // 登录成功
        $_SESSION['user_id'] = $userId; 
        header('Location: index.php');
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
