<?php

require 'vendor/autoload.php';  // Autoload Composer dependencies

// use Dotenv\Dotenv;

// // Load the .env file
// $dotenv = Dotenv::createImmutable(__DIR__);
// $dotenv->load();

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

<nav class="navbar bg-dark sticky-top" data-bs-theme="dark">
  <div class="container">
    <a class="navbar-brand">To-Do List</a>
        <div class="d-flex">
            <!-- 登录链接 -->
            <?php if (isset($_SESSION['email'])): ?>
                <a href="public/logout.php" class="btn btn-danger">LOGOUT</a>
            <?php else: ?>
                <a href="public/user_login.html" class="btn btn-light">LOGIN</a>
            <?php endif; ?>
        </div>
  </div>
</nav>

<div class="container mt-4">
    <?php if (isset($_SESSION['email'])): ?>
        <a href="#" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addTodoModal">NEW TODO</a>
    <?php endif; ?>
</div>


<?php if (isset($_SESSION['email'])): ?>
<?php include 'public/add_todo_form.php' ?>

<div class="container mt-4">
    <div class="row">
    <?php foreach ($todos as $todo): ?>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="public/update.php" id="form-<?php echo htmlspecialchars($todo['id']); ?>">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($todo['id']); ?>">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($todo['email']); ?>">
                    <?php if (isset($_SESSION['email'])): ?>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-dark ms-2" id="edit-save-<?php echo htmlspecialchars($todo['id']) ?>" onclick="editTodo(<?php echo htmlspecialchars($todo['id']); ?>, event)">EDIT</button>
                            <a href="public/delete.php?id=<?php echo $todo['id']; ?>" class="btn btn-danger">Delete</a>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="title-<?php echo htmlspecialchars($todo['id']); ?>" class="form-label">Title</label>
                        <input type="text" id="title-<?php echo htmlspecialchars($todo['id']); ?>" name="title" class="form-control" value="<?php echo htmlspecialchars($todo['title']); ?>" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="description-<?php echo htmlspecialchars($todo['id']); ?>" class="form-label">Description</label>
                        <textarea id="description-<?php echo htmlspecialchars($todo['id']); ?>" name="description" class="form-control" rows="4" required readonly><?php echo htmlspecialchars($todo['description']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status-<?php echo htmlspecialchars($todo['id']); ?>">Status</label>
                        <select name="status" class="form-select" id="status-<?php echo htmlspecialchars($todo['id']); ?>" disabled>
                            <option value="Pending" <?php echo $todo['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Completed" <?php echo $todo['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="priority-<?php echo htmlspecialchars($todo['id']); ?>">Priority</label>
                        <select name="priority" class="form-select" id="priority-<?php echo htmlspecialchars($todo['id']); ?>" disabled>
                            <option value="High" <?php echo $todo['priority'] === 'High' ? 'selected' : ''; ?>>High</option>
                            <option value="Middle" <?php echo $todo['priority'] === 'Middle' ? 'selected' : ''; ?>>Middle</option>
                            <option value="Low" <?php echo $todo['priority'] === 'Low' ? 'selected' : ''; ?>>Low</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end">
                        <?php
                        $dateTime = new DateTime($todo['updated_at'], new DateTimeZone('UTC')); // 假设 $updatedAt 是 UTC 时间
                        $formattedDate = $dateTime->format('Y-m-d H:i:s'); // 格式化为字符串
                        ?>
                        <p class="card-text">Last Edit: <?php echo htmlspecialchars($formattedDate); ?></p>
                    </div> 
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
    </div>
</div>

<?php endif; ?>

<script>
    function editTodo(id, event) {
        // 解锁输入框
        document.getElementById('title-' + id).removeAttribute('readonly');
        document.getElementById('description-' + id).removeAttribute('readonly');
        document.getElementById('status-' + id).removeAttribute('disabled');
        document.getElementById('priority-' + id).removeAttribute('disabled');

        let edit_button = event.target;

        toggleText(edit_button, id);

    }

    function toggleText(button, id) {

        if (button.innerHTML == "EDIT") {
            document.getElementById('title-' + id).removeAttribute('readonly');
            document.getElementById('description-' + id).removeAttribute('readonly');
            document.getElementById('status-' + id).removeAttribute('disabled');
            document.getElementById('priority-' + id).removeAttribute('disabled');
            button.innerHTML = "SAVE";
        } else if (button.innerHTML == "SAVE") {

            // 读取表单数据
        const title = document.getElementById('title-' + id).value;
        const description = document.getElementById('description-' + id).value;
        const status = document.getElementById('status-' + id).value;
        const priority = document.getElementById('priority-' + id).value;

        // 创建一个对象保存表单数据（可选）
        const formData = {
            id: id,
            title: title,
            description: description,
            status: status,
            priority: priority,
        };

        // 打印表单数据（调试用）
        console.log(formData);

        // 提交表单
        document.getElementById('form-' + id).submit();
        document.getElementById('title-' + id).setAttribute('readonly', 'readonly');
        document.getElementById('description-' + id).setAttribute('readonly', 'readonly');
        document.getElementById('status-' + id).setAttribute('disabled', 'disabled');
        document.getElementById('priority-' + id).setAttribute('disabled', 'disabled');
       
        button.innerHTML = "EDIT";

        } else {
            null
        }
    }
        
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</body>
</html>
