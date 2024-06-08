<?php
require_once('./dbconnect.php');
session_start();

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
    <link rel="stylesheet" href="./assets/style/sp/style.css">
    <link rel="stylesheet" href="./assets//style/components/splideDetail.css">
    <script src="./assets/script/script.js" defer></script>
    <script src="./assets/script/narrow.js" defer></script>
    <script src="./assets/script/userHeader.js" defer></script>
    <script src="./assets/script/splideDetail.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
</head>

<body>
    <!-- ヘッダー　えり -->
    <?php
    require_once('./components/header.php');
    ?>
    <!-- ヘッダー終わり -->

    <!-- ゆづき -->
    <main>
        <?php require_once('./components/splide.php'); ?>
        <section class="agency-info">
            <?php require_once('./components/search-agency-info.php'); ?>
            <?php require_once('./components/search-narrow.php'); ?>
        </section>
    </main>
    <!-- えり -->
    <a href="./keep.php" class="keepbutton">
        <p>キープリスト一覧</p>
        <p><?= count($_SESSION['keep_list']) ?>件</p>
    </a>
</body>


</html>


<?php
// echo '<pre>';
// var_dump($agency);
// echo '</pre>';
?>