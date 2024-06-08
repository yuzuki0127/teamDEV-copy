<?php
require_once('./dbconnect.php');
session_start();

$users = $dbh->query('SELECT * FROM user')->fetchAll(PDO::FETCH_ASSOC);
$user_info = $dbh->query('SELECT * FROM user_info')->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $uKey => $user) {
    $user["user"] = [];
    foreach ($user_info as $fKey => $info) {
        if ($info["id"] == $user["user_id"]) {
            $user["user"][] = $info;
        }
    }
    $users[$uKey] = $user;
}


?>

<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CRAFT 就活エージェンシー比較</title>
    <link rel="stylesheet" href="./assets/style/reset.css" />
    <link rel="stylesheet" href="./assets/style/style.css" />
    <link rel="stylesheet" href="./assets/style/components/header.css">
    <link rel="stylesheet" href="./assets/style/sp/header.css">
    <link rel="stylesheet" href="./assets/style/components/popup.css">
    <link rel="stylesheet" href="./assets//style/components/splideDetail.css">
    <link rel="stylesheet" href="./assets/style/sp/style.css">
    <script src="./assets/script/script.js" defer></script>
    <script src="./assets/script/userHeader.js" defer></script>
    <script src="./assets/script/splideDetail.js" defer></script>
    <script src="./assets/script/agency-info.js" defer></script>
    <!-- <link rel="stylesheet" href="../node_modules/@splidejs/splide/dist/css/splide.min.css"> -->
    <!-- <script src="../node_modules/@splidejs/splide/dist/js/splide.min.js"></script> -->
    <!-- <link rel="stylesheet" href="sweetalert2.min.css"> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="
https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js
"></script>
<link href="
https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css
" rel="stylesheet">

</head>


<body>

    <?php
    require_once('./components/header.php');
    ?>

    <main>
        <?php require_once('./components/splide.php'); ?>
        <section class="agency-info">
            <?php require_once('./components/narrow.php'); ?>
            <?php require_once('./components/agency-info.php'); ?>
        </section>
    </main>

    <!-- localstrage使用時 -->
    <!-- <form action="http://localhost:8080/keep.php" method="POST" id="keepForm">
        <input type="hidden" name="keepList" id="keepListInput" value="">
        <button type="button" class="keepbutton" id="keepList-form">キープリスト一覧</button>
    </form> -->

    <!-- session使用時 -->
    <a href="./keep.php" class="keepbutton">
        <p>キープリスト一覧</p>
        <p><?= count($_SESSION['keep_list']) ?>件</p>
    </a>
</body>

</html>


<?php
// echo '<pre>';
// var_dump($_SESSION['keep_list']);
// echo '</pre>';

// echo '<pre>';
// var_dump($users);
// echo '</pre>';

// echo '<pre>';
// var_dump($agencies);
// echo '</pre>';
?>