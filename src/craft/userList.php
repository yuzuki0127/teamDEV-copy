<?php

require_once('../dbconnect.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: http://localhost:8080/craft/craftLogin.php");
    exit; // リダイレクトした後、スクリプトの実行を終了する
}

// 年卒表示
if (isset($_POST['stuSearch'])) {
    $selected_year = $_POST['graduate'];
    if ($selected_year != "NoYear") {
        $user_info = $dbh->prepare('SELECT * FROM user_info WHERE graduate_year = :graduate_year');
        $user_info->execute(['graduate_year' => $selected_year]);
    } else {
        // なしを選択した場合は全てのユーザー情報を取得
        $user_info = $dbh->query('SELECT * FROM user_info');
    }
} else {
    $user_info = $dbh->query('SELECT * FROM user_info');
}

// 文理選択
if (isset($_POST['stuSearch'])) {
    $selected_context = $_POST['ContextOfUse'];
    if ($selected_context != "NoSelect") {
        if ($selected_year != "NoYear") {
            $user_info = $dbh->prepare('SELECT * FROM user_info WHERE selection = :selection AND graduate_year = :graduate_year');
            $user_info->execute(['selection' => ($selected_context == "lit" ? 1 : 0), 'graduate_year' => $selected_year]);
        } else {
            $user_info = $dbh->prepare('SELECT * FROM user_info WHERE selection = :selection');
            $user_info->execute(['selection' => ($selected_context == "lit" ? 1 : 0)]);
        }
    }
}

$user_info = $user_info->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>クラフト学生情報</title>
    <link rel="stylesheet" href="../assets/style/reset.css">
    <link rel="stylesheet" href="../assets/style/agency/agencyHeader.css">
    <link rel="stylesheet" href="../assets/style/craft/craftSidebar.css">
    <link rel="stylesheet" href="../assets/style/agency/agencyHome.css">
    <link rel="stylesheet" href="../assets/style/agency/userInfo-popup.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
    <script src="../assets/script/userInfo.js" defer></script>
</head>

<body>
    <!-- ヘッダー -->
    <?php require_once('./components/craftHeader.php'); ?>
    <main>
        <div class="craftContainer">
            <!-- craftサイドバー ホーム-->
            <aside class="craft-inner">
                <div class="craft-sidebar">
                    <ul class="craft-sidebarContainer">
                        <li><a href="./index.php" class="craft-sidebarItem">ホーム</a></li>
                        <li><a href="./craftAgency.php" class="craft-sidebarItem">エージェンシー情報</a></li>
                        <li><a href="userList.php" class="craft-sidebarItem current">学生情報</a></li>
                    </ul>
                </div>
            </aside>

            <!-- ホーム画面 -->
            <section class="user-information">
                <div class="user-informationContainer">
                    <div class="user-informationTitle">
                        <p>学生情報</p>
                    </div>
                    <!-- <div class="user-informationSearch">
                        <input type="text" placeholder="ユーザー名検索" class="username-input">
                    </div> -->
                    <div class="user-informationInner">
                        <div class="user-informationTab">
                            <form action="userList.php" method="POST">
                                <ul class="user-informationTabMenu">
                                    <li class="user-informationTabItem1">
                                        <select name="graduate">
                                            <option value="NoYear" <?php if (isset($_POST['graduate']) && $_POST['graduate'] == 'NoYear') echo 'selected'; ?>>なし</option>
                                            <?php
                                            $current_year = date("Y");
                                            for ($year = $current_year; $year <= $current_year + 4; $year++) {
                                                echo "<option value='$year'";
                                                if (isset($_POST['graduate']) && $_POST['graduate'] == $year) echo ' selected';
                                                echo ">" . $year . "年卒</option>";
                                            }
                                            ?>
                                        </select>

                                    </li>
                                    <li class="user-informationTabItem2">
                                        <select name="ContextOfUse">
                                            <option value="NoSelect" <?php if (isset($_POST['ContextOfUse']) && $_POST['ContextOfUse'] == 'NoSelect') echo 'selected'; ?>>なし</option>
                                            <option value="lit" <?php if (isset($_POST['ContextOfUse']) && $_POST['ContextOfUse'] == 'lit') echo 'selected'; ?>>文系</option>
                                            <option value="sci" <?php if (isset($_POST['ContextOfUse']) && $_POST['ContextOfUse'] == 'sci') echo 'selected'; ?>>理系</option>
                                        </select>

                                    </li>
                                    <li class="user-informationTabItem3">
                                        <input type="submit" name="stuSearch" value="検索"></input>
                                    </li>
                                </ul>
                            </form>
                        </div>
                        <?php require_once('./components/userInfo.php'); ?>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>

</html>

<?php
// echo '<pre>';
// var_dump($user_info);
// echo '</pre>';
?> 
