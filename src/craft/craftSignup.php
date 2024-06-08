<?php
require_once('../dbconnect.php');
$message = '';

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // エラー詳細を表示


// フォームからデータが送信されたかどうかをチェック
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームから送信されたデータを取得
    $name = $_POST["craftName"];
    $email = $_POST["craftEmail"];
    $password = $_POST["password"];
    $rePassword = $_POST["rePassword"];

    // パスワードの空チェックを追加
    if (empty($password) || empty($rePassword)) {
        echo "エラー: パスワードを入力してください";
    } elseif ($password != $rePassword) {
        echo "エラー: パスワードとパスワード確認用フィールドの値が一致しません";
    } else {
        // パスワードをハッシュ化する
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 全ての情報が入力されているかどうかを確認
        if (!empty($name) && !empty($email) && !empty($hashedPassword)) {
            try {
                $sql = "INSERT INTO craft (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
                if ($dbh->exec($sql)) {
                    $user_id = $dbh->lastInsertId();
                    // ユーザーIDをセッションに保存
                    $_SESSION['user_id'] = $user_id;
                    header("Location: index.php");
                    exit(); // リダイレクト後にはスクリプトを終了することが推奨されます
                } else {
                    echo "エラー: データベースへの挿入に失敗しました";
                }
            } catch (PDOException $e) {
                echo "エラー: " . $e->getMessage();
            }
        } else {
            echo "エラー: 全ての情報を入力してください";
        }
    }
}





// パスワードとパスワード確認用フィールドのデータが一致するかどうかをチェック
if ($password != $rePassword) {
    echo "エラー: パスワードとパスワード確認用フィールドの値が一致しません";
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
            echo "エラー: データベースへの挿入に失敗しました";
        }
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
} else {
    echo "エラー: 全ての情報を入力してください";
}


$dbh = null;


//     // データベースに接続
//     // $dbh = new mysqli($servername, $username, $password_db, $dbname);

//     // 接続エラーがあるかどうかをチェック
//     if ($dbh->connect_error) {
//         die("データベースに接続できません: " . $conn->connect_error);
//     }

//     // データを挿入するSQLクエリを作成
//     $sql = "INSERT INTO craft (name, email, password) VALUES ('$name', '$email', '$password')";

//     // クエリを実行し、結果をチェック
//     if ($dbh->query($sql) === TRUE) {
//         echo "新しいレコードが正常に挿入されました";
//     } else {
//         echo "エラー: " . $sql . "<br>" . $dbh->error;
//     }


//     echo $sql;



//     // データベース接続を閉じる
//     $dbh->close();
// }

// try {
//     // パスワードをハッシュ化する
// $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
//     // データを挿入するSQLクエリを作成
//     $sql = "INSERT INTO craft (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";

//     // クエリを実行し、結果をチェック
//     if ($dbh->exec($sql)) {
//         // index.phpにリダイレクトする
//         header("Location: index.php");
//         exit(); // リダイレクト後にはスクリプトを終了することが推奨されます
//     } else {
//         echo "エラー: " . $sql . "<br>" . $dbh->errorInfo()[2];
//     }
// } catch (PDOException $e) {
//     echo "エラー: " . $e->getMessage();
// }

// // データベース接続を閉じる
// $dbh = null;
// }


?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>craftサインアップ</title>
    <link rel="stylesheet" href="../assets/style/reset.css" />
    <link rel="stylesheet" href="../assets/style/craft/craftSignup.css">
    <link rel="stylesheet" href="../assets/style/craft/craftSidebar.css">
    <link rel="stylesheet" href="../assets/style/agency/agencyHeader.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>





<!-- ヘッダー -->
<?php require_once('../craft/components/craftHeader.php'); ?>

<!-- エージェンシー情報登録フォーム -->
<main>
    <section class="craft-signup-container">
        <div class="box-signup">
            <div class="craft-regiContainer">
                <div class="craft-regiTitle">
                    <p class="craftSignup-title">アカウント作成</p>
                </div>
                <form action="craftSignup.php" method="POST">
                    <div class="craft-regiForm">
                        <div class="craft-regiFormContainer">
                            <div class="craft-regiFormInner">
                                <input type="text" name="craftName" class="craft-regiFormInput craft-regiName form-control" placeholder="お名前">
                            </div>
                            <div class="craft-regiFormInner">
                                <input type="text" name="craftEmail" class="craft-regiFormInput craft-regiFormEmail form-control" placeholder="メールアドレス">
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
                <div class="craft-loginLead"><a href="../craft/craftLogin.php">ログインはこちら</a></div>
            </div>
        </div>
    </section>
</main>