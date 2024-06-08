<?php

$host = 'db'; // Docker ComposeでのMySQLサービス名
$dbname = 'citron'; // データベース名
$user = 'root'; // データベースユーザー
$password = 'citron'; // データベースパスワード
$port = 3306; // MySQLポート（Docker Composeでポートマッピングされたポート）

try {
    $dbh = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

} catch (PDOException $e) {
    exit($e->getMessage());
}
