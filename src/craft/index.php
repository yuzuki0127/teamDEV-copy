<?php
require_once('../dbconnect.php');
require_once('../vendor/autoload.php');
session_start();

use Verot\Upload\Upload;
// // セッションの有効期限を設定（例：30分）
// $inactive = 18; // 30分（秒数で指定）

// // 最終アクセス時刻がセッションの有効期限を超えている場合はログアウト
// if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
//     session_unset();     // セッション変数を全て削除
//     session_destroy();   // セッションを破棄
//     header("Location: login.php"); // ログイン画面にリダイレクト
//     exit;
// }

// // 最終アクセス時刻を更新
// $_SESSION['last_activity'] = time();

if (!isset($_SESSION['user_id'])) {
    header("Location: http://localhost:8080/craft/craftLogin.php");
    exit; // リダイレクトした後、スクリプトの実行を終了する
}





// 削除機能
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'reject') !== false) {
            $agency_id = intval(str_replace('reject', '', $key));
            
            $delete_agency = $dbh->prepare("DELETE FROM agency WHERE id = ?");
            $delete_agency->execute([$agency_id]);

            $delete_feature = $dbh->prepare("DELETE FROM feature WHERE agency_id = ?");
            $delete_feature->execute([$agency_id]);

            $delete_levels = $dbh->prepare("DELETE FROM levels WHERE agency_id = ?");
            $delete_levels->execute([$agency_id]);

            $delete_editor = $dbh->prepare("DELETE FROM editor WHERE agency_id = ?");
            $delete_editor->execute([$agency_id]);

            header("Location: {$_SERVER['PHP_SELF']}");
            exit;
        }
    }
}

//承認機能
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'approval') !== false) {
            $agency_id = intval(str_replace('approval', '', $key));
            
            $update_approval = $dbh->prepare("UPDATE agency SET approval = 1 WHERE id = ?");
            $update_approval->execute([$agency_id]);

            header("Location: {$_SERVER['PHP_SELF']}");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>craftホーム画面</title>
    <link rel="stylesheet" href="../assets/style/reset.css" />
    <link rel="stylesheet" href="../assets/style/craft/craftSidebar.css">
    <link rel="stylesheet" href="../assets/style/craft/craftHome.css" />
    <link rel="stylesheet" href="../assets/style/agency/agencyHeader.css">
    <link rel="stylesheet" href="../assets/style/components/popup.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
    <script src="../assets/script/agency-info.js" defer></script>
    <!-- <script src="../assets/script/craftApproval.js" defer></script> -->
</head>

<body>
    <?php require_once('../craft/components/craftHeader.php'); ?>

    <main class="agency-check">
        <!-- craftサイドバー ホーム-->
        <aside class="craft-inner">
            <div class="craft-sidebar">
                <ul class="craft-sidebarContainer">
                    <li><a href="index.php" class="craft-sidebarItem current">ホーム</a></li>
                    <li><a href="./craftAgency.php" class="craft-sidebarItem">エージェンシー情報</a></li>
                    <li><a href="./userList.php" class="craft-sidebarItem">学生情報</a></li>
                </ul>
            </div>
        </aside>

        <div class="agency-inner">

            <?php require_once('./components/cumulate.php'); ?>

            <div class="agency-checkList">

                <?php require_once('./components/newUpdated.php'); ?>

                <?php require_once('./components/agencyApproval.php'); ?>

            </div>
        </div>
        </div>
    </main>
</body>

</html>