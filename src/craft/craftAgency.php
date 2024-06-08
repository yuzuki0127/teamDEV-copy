<?php
require_once('../dbconnect.php');
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: http://localhost:8080/craft/craftLogin.php");
    exit; // リダイレクトした後、スクリプトの実行を終了する
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST as $key => $value) {
        if (is_numeric($key)) {
            // Agency ID を取得
            $agency_id = intval($key);

            // $image_name = $dbh->prepare("SELECT image FROM agency WHERE id = ?");
            // $image_name->execute([$agency_id]);
            // $image_path = __DIR__ . '../assets/img/agencies/' . $image_name;
            // if (file_exists($image_path)) {
            //     unlink($image_path);
            // }
            // Agency を削除
            $delete_agency = $dbh->prepare("DELETE FROM agency WHERE id = ?");
            $delete_agency->execute([$agency_id]);

            // 関連する feature を削除
            $delete_feature = $dbh->prepare("DELETE FROM feature WHERE agency_id = ?");
            $delete_feature->execute([$agency_id]);

            // 関連する levels を削除
            $delete_levels = $dbh->prepare("DELETE FROM levels WHERE agency_id = ?");
            $delete_levels->execute([$agency_id]);

            // 関連する editor を削除
            $delete_editor = $dbh->prepare("DELETE FROM editor WHERE agency_id = ?");
            $delete_editor->execute([$agency_id]);
        }
    }
    // 削除が完了したらリダイレクトする
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>craftエージェンシー情報画面</title>
    <link rel="stylesheet" href="../assets/style/reset.css">
    <link rel="stylesheet" href="../assets/style/craft/craftSidebar.css">
    <link rel="stylesheet" href="../assets/style/craft/craftAgency.css">
    <link rel="stylesheet" href="../assets/style/agency/agencyHeader.css">
    <link rel="stylesheet" href="../assets/style/components/popup.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
    <script src="../assets/script/agency-info.js" defer></script>
</head>

<body>
    <?php require_once('./components/craftHeader.php'); ?>

    <main class="agency-check">
        <!-- craftサイドバー エージェンシー-->
        <aside class="craft-inner">
            <div class="craft-sidebar">
                <ul class="craft-sidebarContainer">
                    <li><a href="./index.php" class="craft-sidebarItem">ホーム</a></li>
                    <li><a href="craftAgency.php" class="craft-sidebarItem current">エージェンシー情報</a></li>
                    <li><a href="./userList.php" class="craft-sidebarItem">学生情報</a></li>
                </ul>
            </div>
        </aside>

        <?php require_once('./components/agencyList.php'); ?>
    </main>
</body>

</html>

<?php
// echo '<pre>';
// var_dump($agencies);
// echo '</pre>';
?>