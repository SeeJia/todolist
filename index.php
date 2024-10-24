<?php

require 'vendor/autoload.php';  // Autoload Composer dependencies

use Dotenv\Dotenv;

// Load the .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

session_start(); // 开始会话

$apiUrl = 'https://ibbhnrhodkqgzndymooe.supabase.co/rest/v1/todolist?select=*'; 
$apiKey = $_ENV['SUPABASE_API_KEY'];
$bearerToken = $_ENV['SUPABASE_BEARER_TOKEN'];

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

$todos = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>


    <!-- 登录链接 -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="add_todo.php" class="btn btn-primary">Add Todo</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <?php else: ?>
        <p><a href="user_login.php" class="btn btn-primary">Login</a></p>
    <?php endif; ?>

<div class="container mt-5">
        <h1>To-Do List</h1>
        <div class="row">
            <?php foreach ($todos as $todo): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($todo['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($todo['description']); ?></p>
                            <p class="card-text"><?php echo htmlspecialchars($todo['status']); ?></p>
                            <p class="card-text"><?php echo htmlspecialchars($todo['priority']); ?></p>
                            <p class="card-text"><?php echo htmlspecialchars($todo['updated_at']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</body>
</html>
