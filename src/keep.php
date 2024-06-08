<?php
require_once('./dbconnect.php');
session_start();

$agencies = $dbh->query('SELECT * FROM agency')->fetchAll(PDO::FETCH_ASSOC);
$features = $dbh->query("SELECT * FROM feature INNER JOIN feature_info ON feature.feature_id = feature_info.id;")->fetchAll(PDO::FETCH_ASSOC);
$feature_info = $dbh->query("SELECT * FROM feature_info")->fetchAll(PDO::FETCH_ASSOC);
$levels = $dbh->query("SELECT * FROM levels INNER JOIN levels_info ON levels.levels_id = levels_info.id;")->fetchAll(PDO::FETCH_ASSOC);
$levels_info = $dbh->query("SELECT * FROM levels_info")->fetchAll(PDO::FETCH_ASSOC);

foreach ($agencies as $aKey => $agency) {
    $agency["feature"] = [];
    foreach ($features as $fKey => $feature) {
        if ($feature["agency_id"] == $agency["id"]) {
            $agency["feature"][] = $feature;
        }
    }
    $agencies[$aKey] = $agency;
}
foreach ($agencies as $aKey => $agency) {
    $agency["levels"] = [];
    foreach ($levels as $lKey => $level) {
        if ($level["agency_id"] == $agency["id"]) {
            $agency["levels"][] = $level;
        }
    }
    $agencies[$aKey] = $agency;
}

// 削除ボタンが押された場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agency_id'])) {
    $delete_agency_id = $_POST['agency_id'];

    // $_SESSION['keep_list']から削除
    $key = array_search($delete_agency_id, $_SESSION['keep_list']);
    if ($key !== false) {
        unset($_SESSION['keep_list'][$key]);
        // インデックスを再構築
        $_SESSION['keep_list'] = array_values($_SESSION['keep_list']);
    }
}

// 応募ボタンが押されたときの処理
if (isset($_POST['selectedAgencies'])) {
    // JavaScriptから送信されたJSON形式の文字列をデコードして配列に変換
    $selectedAgencies = json_decode($_POST['selectedAgencies']);
    
    // 受け取った配列をセッションに保存
    $_SESSION['apply_list'] = $selectedAgencies;

    // $_SESSION['apply_list']に値が入っている場合かつ1個以上の要素を持つ場合はリダイレクトする
    if (isset($_SESSION['apply_list'])) {
        header("Location: http://localhost:8080/userEntryForm.php");
        exit;
    }
} else {
    // データが送信されていない場合の処理
    echo "No data received.";
}
?>

<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>キープリスト一覧</title>
    <link rel="stylesheet" href="./assets/style/reset.css" />
    <link rel="stylesheet" href="./assets/style/components/userKeep.css" />
    <link rel="stylesheet" href="./assets/style/components/header.css" />
    <link rel="stylesheet" href="./assets/style/sp/header.css" />
    <link rel="stylesheet" href="./assets/style/sp/userKeep.css">
    <link rel="stylesheet" href="./assets/style/components/popup.css">
    <script src="./assets/script/keep.js" defer></script>
    <script src="./assets/script/userHeader.js" defer></script>
    <script src="./assets/script/agency-info.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- ヘッダー -->
    <?php require_once('./components/header.php'); ?>

    <main class="agency-check">
        <section class="agency-inner">
            <div class="keep-info">
                <p class="agency-infoTitle">キープ中のエージェンシー</p>
                <div class="displayed-result">
                    <p class="displayed-resultTitle">表示件数</p>
                    <p class="displayed-resultNum"><?= count($_SESSION['keep_list']); ?>件</p>
                </div>
            </div>
            <?php
            if (empty($_SESSION['keep_list'])) {
                echo "キープリストが登録されていません！！";
            }
            ?>
            <?php require_once('./components/keep-agency-info.php'); ?>

            <section class="keep-selectBtn">
                <a href="./index.php" class="keep-returnBtn">戻る</a>
                <form action="http://localhost:8080/keep.php" method="POST" id="applyForm">
                    <input type="hidden" name="selectedAgencies" id="selectedAgencies">
                    <input class="keep-submitBtn" type="submit" id="keep-submitBtn" name="applyBtn" value="応募する">
                </form>
            </section>
        </section>
    </main>

    <!-- お気に入り選択件数ポップアップ -->
    <div class="pop-up">
        <p class="popup-title">選択件数</p>
        <p class="popup-number">0/<?= count($_SESSION['keep_list']); ?>件</p>
    </div>
</body>

</html>
