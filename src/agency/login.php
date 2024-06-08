<?php
require_once('../dbconnect.php');
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // バリデーション
    if (empty($_POST['email'])) {
        $message = 'メールアドレスは必須項目です。';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $message = '正しいEメールアドレスを指定してください。';
    } elseif (empty($_POST['password'])) {
        $message = 'パスワードは必須項目です。';
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // データベースへの接続
        $stmt = $dbh->prepare('SELECT * FROM editor_info WHERE email = :email');
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        $editor = $user["id"];
        $stmt = $dbh->prepare('SELECT * FROM editor WHERE editor_id = :editor');
        $stmt->bindValue(':editor', $editor);
        $stmt->execute();
        $editor_info = $stmt->fetch();

        // ユーザーがa存在し、パスワードが正しいか確認
        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["agencyID"] = $editor_info["agency_id"]; 
            header('Location: /agency/index.php');
            exit();
        } else {
            // 認証失敗: エラーメッセージをセット
            $message = 'メールアドレスまたはパスワードが間違っています。';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>エージェンシー ログイン</title>
<!-- スタイルシート読み込み -->
<link rel="stylesheet" href="../assets/styles/reset.css">
<link rel="stylesheet" href="../assets/style/agency/agencyHeader.css">
<link rel="stylesheet" href="../assets/style/agency/login.css">
<!-- Google Fonts読み込み -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
</head>

<body>
    <?php require_once('./components/agencyHeader.php'); ?>

    <main>
    <div class="container">
        <p class="login-title">ログイン</p>
        <?php if ($message !== '') { ?>
            <p style="color: red;"><?= $message ?></p>
        <?php }; ?>
        <form method="POST" class="form">
            <div class="form-container">
            <!-- <label for="email" class="form-label">Email</label> -->
            <input type="email" name="email" placeholder="IDまたはメールアドレス" class="form-control" id="email" value="">
            </div>
        <div class="form-container">
            <!-- <label for="password" class="form-label">パスワード</label> -->
            <input type="password" name="password" placeholder="パスワード" id="password" class="form-control">
        </div>
        <div class="btn-container">

        <button type="submit"class="btn submit">ログイン</button>

        </div>
        </form>
        <div class="lead-box"><a href="http://localhost:8080/agency/agencyRegister.php" class="signup-lead">サインアップ・情報登録はこちら</a></div>
    </div>
    </main>
</div>
<script>
    // const EMAIL_REGEX = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/
    // const submitButton = document.querySelector('.btn.submit')
    // const emailInput = document.querySelector('.email')
    // inputDoms = Array.from(document.querySelectorAll('.form-control'))
    // inputDoms.forEach(inpuDom => {
    // inpuDom.addEventListener('input', event => {
    //     const isFilled = inputDoms.filter(d => d.value).length === inputDoms.length
    //     submitButton.disabled = !(isFilled && EMAIL_REGEX.test(emailInput.value))
    // })
    // })
</script>
</body>

</html>