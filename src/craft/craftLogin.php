<?php
require_once('../dbconnect.php'); // データベース接続のためのファイルを読み込む
$message = '';

session_start();


// データベースに接続
// try {
//     $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
// } catch (PDOException $e) {
//     echo "データベースに接続できませんでした。エラー: " . $e->getMessage();
//     exit;
// }

// POSTメソッドで送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $craftEmail = $_POST['email'];
    $password = $_POST['password'];

    // データベースからユーザー情報を取得
    $stmt = $dbh->prepare('SELECT * FROM craft WHERE email = :email');
    $stmt->bindParam(':email', $craftEmail);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ユーザーが存在し、パスワードが一致するかを確認
    if ($user && password_verify($password, $user['password'])) {
        // ログイン成功時の処理
        $_SESSION['user_id'] = $user['id']; // セッションにユーザーIDを保存
        header('Location: http://localhost:8080/craft/index.php');
        exit;
    } else {
        // ログイン失敗時の処理
        echo "メールアドレスまたはパスワードが間違っています。";
    }
}
?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>クラフトログイン画面</title>
    <link rel="stylesheet" href="../assets/style/reset.css">
    <link rel="stylesheet" href="../assets/style/craft/craftLogin.css">
    <link rel="stylesheet" href="../assets/style/agency/agencyHeader.css">
    <!-- Google Fonts読み込み -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">

</head>
<body>
    
</body>
</html>

<!-- ヘッダー -->
<?php require_once('../craft/components/craftHeader.php'); ?>

    <!-- エージェンシー情報登録フォーム -->
    <main>
        <!-- <section class="craft-regi"> -->
            <div class="Container">
                <div class="craft-loginTitle">
                    <p class="login-title">ログイン</p>
                </div>
                <form action="craftLogin.php" method="POST" class="form">
                <div class="craft-regiForm">
                    <div class="craft-regiFormContainer">
                    <div class="craft-regiFormInner">
                            <!-- <label class="craft-regiFormText">メールアドレス</label> -->
                            <input type="email" name="email" placeholder="IDまたはメールアドレス" class="form-control" id="email">
                    </div>
                    <div class="craft-regiFormInner">
                            <!-- <label class="craft-regiFormText">パスワード</label> -->
                            <input type="password" name="password" placeholder="パスワード" id="password" class="form-control">
                    </div>
                    <div class="craft-formSubmitContainer">
                <div class="craft-regiFormSubmit">
                    <button type="submit" class="craft-formSubmitButton">ログイン</button>
                </div>
                <div><a href="../craft/craftSignup.php" class="signup-lead">サインアップはこちら</a></div>
            </div>
</form>
</div>
</main>