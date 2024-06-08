<?php
if (!isset($_SESSION["agencyID"])) {
    header("Location: http://localhost:8080/agency/login.php");
    exit; // リダイレクトした後、スクリプトの実行を終了する
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agency Home</title>
    <!-- CSSファイルの読み込み -->
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h1>Welcome to Agency Home</h1>
        <p>Hello, <?php echo $_SESSION['id']; ?>!</p>
        <a href="logout.php">ログアウト</a>
    </div>
</body>

</html>
