<?php 

$apiUrl = 'https://ibbhnrhodkqgzndymooe.supabase.co/rest/v1/todolist?select=*'; 
$apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImliYmhucmhvZGtxZ3puZHltb29lIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MjkxNTA4ODQsImV4cCI6MjA0NDcyNjg4NH0.p-OQbxfMiHbegbW5-2YvflGtlCqU6NJ_NGJDyK6Ir_M';  
$bearerToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImliYmhucmhvZGtxZ3puZHltb29lIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MjkxNTA4ODQsImV4cCI6MjA0NDcyNjg4NH0.p-OQbxfMiHbegbW5-2YvflGtlCqU6NJ_NGJDyK6Ir_M';

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