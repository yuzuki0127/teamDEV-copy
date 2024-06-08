<?php

require_once('../dbconnect.php');
session_start();

if (!isset($_SESSION["agencyID"])) {
    header("Location: http://localhost:8080/agency/login.php");
    exit; // リダイレクトした後、スクリプトの実行を終了する
}

$message = '';

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // エラー詳細を表示

$agency = $dbh->prepare('SELECT * FROM agency WHERE id = :id');
$agency->execute(['id' => $_SESSION["agencyID"]]);
$Agency = $agency->fetchAll(PDO::FETCH_ASSOC);



// フォームが送信された場合の処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームからのデータを取得
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $rePassword = $_POST["rePassword"];

    // パスワードが空でないことを確認
    if (empty($password) || empty($rePassword)) {
        $message = "エラー: パスワードを入力してください";
    } elseif ($password != $rePassword) {
        $message = "エラー: パスワードとパスワード確認用フィールドの値が一致しません";
    } else {
        // パスワードをハッシュ化
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 全ての情報が入力されているか確認
        if (!empty($name) && !empty($email) && !empty($hashedPassword)) {
            try {
                // editor_info テーブルへの挿入
                $sql_editor_info = "INSERT INTO editor_info (editor_name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
                $stmt_editor_info = $dbh->prepare($sql_editor_info);
                $stmt_editor_info->execute();

                // 挿入したデータの ID を取得
                $editor_info_id = $dbh->lastInsertId();

                // agencyID と editor_info_id を使用して editor テーブルに挿入
                $agencyID = $_SESSION["agencyID"];
                $sql_editor = "INSERT INTO editor (agency_id, editor_id) VALUES ('$agencyID', '$editor_info_id')";
                $stmt_editor = $dbh->prepare($sql_editor);
                if ($stmt_editor->execute()) {
                    // リダイレクト
                    header("Location: index.php");
                    exit();
                } else {
                    $message = "エラー: editor テーブルへの挿入に失敗しました";
                }
            } catch (PDOException $e) {
                $message = "エラー: " . $e->getMessage();
            }
        } else {
            $message = "エラー: 全ての情報を入力してください";
        }
    }





    // パスワードとパスワード確認用フィールドのデータが一致するかどうかをチェック
    if ($password != $rePassword) {
        $message = "エラー: パスワードとパスワード確認用フィールドの値が一致しません";
        // エラー処理などを行う
    } else {
        // 一致する場合はデータベースに挿入する処理を続行する
        // パスワードをハッシュ化する
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    }

    if (!empty($name) && !empty($email) && !empty($hashedPassword)) {
        try {
            $sql = "INSERT INTO craft (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
            if ($dbh->exec($sql)) {
                header("Location: index.php");
                exit(); // リダイレクト後にはスクリプトを終了することが推奨されます
            } else {
                $message = "エラー: データベースへの挿入に失敗しました";
            }
        } catch (PDOException $e) {
            $message = "エラー: " . $e->getMessage();
        }
    } else {
        $message = "エラー: 全ての情報を入力してください";
    }


    $dbh = null;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>craftサインアップ</title>
    <link rel="stylesheet" href="../assets/style/reset.css" />
    <link rel="stylesheet" href="../assets/style/craft/craftHome.css" />
    <link rel="stylesheet" href="../assets/style/agency/agencyHeader.css">
    <link rel="stylesheet" href="../assets/style/craft/craftSidebar.css">
    <link rel="stylesheet" href="../assets/style/craft/craftSignup.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>




<body>
<!-- ヘッダー -->
<?php require_once('./components/agencyHeader.php'); ?>

<!-- エージェンシー情報登録フォーム -->
<main class="craftContainer">
    <section class="craft-inner">
        <p class="sidebar-wel">ようこそ！！</p>
        <p class="sidebar-name"><?= $Agency[0]['agency_name']; ?></p>
        <div class="craft-sidebar">
            <ul class="craft-sidebarContainer">
                <li><a href="./index.php" class="craft-sidebarItem">学生情報</a></li>
                <li><a href="editorSignup.php" class="craft-sidebarItem current">ユーザー登録</a></li>
                <li><a href="./agencyEdit.php" class="craft-sidebarItem">エージェンシー情報編集</a></li>
            </ul>
        </div>
    </section>
    <section class="craft-signup-container">
        <div class="box-signup">
            <div class="craft-regiContainer">
                <div class="craft-regiTitle">
                    <p class="craftSignup-title">新規管理者登録</p>
                </div>
                <form action="editorSignup.php" method="POST">
                    <div class="craft-regiForm">
                        <div class="craft-regiFormContainer">
                            <div class="craft-regiFormInner">
                                <input type="text" name="name" class="craft-regiFormInput craft-regiName form-control" placeholder="お名前">
                            </div>
                            <div class="craft-regiFormInner">
                                <input type="text" name="email" class="craft-regiFormInput craft-regiFormEmail form-control" placeholder="メールアドレス">
                            </div>
                            <div class="craft-regiFormInner">
                                <input type="password" name="password" class="craft-regiFormInput craft-regiFormPassword form-control" placeholder="ログイン用パスワード">
                            </div>
                            <div class="craft-regiFormInner">
                                <input type="password" name="rePassword" class="craft-regiFormInput craft-regiFormRePassword form-control" placeholder="ログイン用パスワード(確認用)">
                            </div>
                            <div class="craft-regiFormSubmit1">
                                <div class="craft-regiFormSubmit">
                                    <button class="craft-regiFormSubmitButton craftSignup-submit">登録</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="craft-loginLead"><a href="./login.php">ログインはこちら</a></div>
            </div>
        </div>
    </section>
</main>
</body>

</html>
<?php
if (isset($message)) {
    echo ($message);
}  ?>

