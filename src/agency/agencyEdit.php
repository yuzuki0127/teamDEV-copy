<?php
require_once('../dbconnect.php');
require_once('../vendor/autoload.php');

use Verot\Upload\Upload;

session_start();

if (!isset($_SESSION["agencyID"])) {
    header("Location: http://localhost:8080/agency/login.php");
    exit; // リダイレクトした後、スクリプトの実行を終了する
}

$agency = $dbh->prepare('SELECT * FROM agency WHERE id = :id');
$agency->execute(['id' => $_SESSION["agencyID"]]);
$Agency = $agency->fetchAll(PDO::FETCH_ASSOC);

$message = "";

$agencyId = $_SESSION['agencyID'];

// エージェンシー情報を取得（IDが$agencyIdのもののみ）
$agencies = $dbh->prepare('SELECT * FROM agency WHERE id = ?');
$agencies->execute([$agencyId]);
$agencies = $agencies->fetchAll(PDO::FETCH_ASSOC);
$features = $dbh->query("SELECT * FROM feature INNER JOIN feature_info ON feature.feature_id = feature_info.id;")->fetchAll(PDO::FETCH_ASSOC);
$feature_info = $dbh->query("SELECT * FROM feature_info")->fetchAll(PDO::FETCH_ASSOC);
$levels = $dbh->query("SELECT * FROM levels INNER JOIN levels_info ON levels.levels_id = levels_info.id;")->fetchAll(PDO::FETCH_ASSOC);
$levels_info = $dbh->query("SELECT * FROM levels_info")->fetchAll(PDO::FETCH_ASSOC);
$category = $dbh->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // エージェンシー情報を取得
    $companyName = $_POST['companyName'];
    $companyStrength = $_POST['companyStrength'];
    $address = $_POST['address'];
    $compatibleTime = $_POST['time'];
    $supplement = $_POST['free'];

    // 現在時刻を取得
    $updatedAt = date("Y-m-d");

    try {
        $dbh->beginTransaction();

        // ファイルアップロード
        $file = $_FILES['image'];
        $lang = 'ja_JP';
        $handle = new Upload($file, $lang);
        if (!$handle->uploaded) {
            throw new Exception($handle->error);
        }
        // ファイルサイズのバリデーション： 5MB
        $handle->file_max_size = '5120000';
        // ファイルの拡張子と MIMEタイプをチェック
        $handle->allowed = array('image/jpeg', 'image/png', 'image/gif');
        // PNGに変換して拡張子を統一
        $handle->image_convert = 'png';
        $handle->file_new_name_ext = 'png';
        // サイズ統一
        $handle->image_resize = true;
        $handle->image_x = 718;
        // アップロードディレクトリを指定して保存
        $handle->process('../assets/img/agencies/');
        if (!$handle->processed) {
            throw new Exception($handle->error);
        }
        $image_name = $handle->file_dst_name;
        // 更新前の画像を削除
        if ($image_name) {
            $image_path = __DIR__ . '../assets/img/agencies/' . $image_name;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        // // 更新前の画像を削除
        // if ($image_name) {
        //     $image_path = __DIR__ . '/../assets/img/agencies/' . $image_name;
        //     if (file_exists($image_path)) {
        //         unlink($image_path);
        //     }
        // }

        // //ファイル動かないとき用
        // $image_name = "";

        // エージェンシー情報の更新
        $agencyUpdateQuery = "UPDATE agency SET agency_name = ?, strong_point = ?, image = ?, compatible_time = ?, address = ?, supplement = ?, updated_at = ? WHERE id = ?";
        $agencyStatement = $dbh->prepare($agencyUpdateQuery);
        $agencyStatement->execute([$companyName, $companyStrength, $image_name, $compatibleTime, $address, $supplement, $updatedAt, $agencyId]);

        // levelsテーブルの更新
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'level') === 0) {
                $levelId = substr($key, 5); // level の部分を削除して id を取得
                $levelUpdateQuery = "UPDATE levels SET level = ? WHERE agency_id = ? AND levels_id = ?";
                $levelUpdateStmt = $dbh->prepare($levelUpdateQuery);
                $levelUpdateStmt->execute([$value, $agencyId, $levelId]);
            }
        }

        // featureテーブルの更新
        foreach ($feature_info as $feature) {
            $checkboxName = 'feature' . $feature['id'];
            $status = isset($_POST[$checkboxName]) ? 1 : 0;

            $featureUpdateQuery = "UPDATE feature SET status = ? WHERE agency_id = ? AND feature_id = ?";
            $featureUpdateStmt = $dbh->prepare($featureUpdateQuery);
            $featureUpdateStmt->execute([$status, $agencyId, $feature['id']]);
        }

        $dbh->commit();
        $_SESSION['message'] = "編集に成功しました。";
        // echo ("登録に成功しました");
        header('Location: http://localhost:8080/agency/index.php');
        exit;
    } catch (PDOException $e) {
        $dbh->rollBack();
        // $_SESSION['message'] = "編集に失敗しました。";
        $message = ("登録に失敗しました: " . $e->getMessage());
        // echo ("登録に失敗しました: " . $e->getMessage()); // エラーメッセージを出力
        // header('Location: http://localhost:8080/agency/agencyEdit.php');
        // exit;
    }
}
?>






<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>エージェンシー情報登録</title>
    <link rel="stylesheet" href="../assets/style/reset.css" />
    <link rel="stylesheet" href="../assets/style/agency/agencyHeader.css">
    <link rel="stylesheet" href="../assets/style/craft/craftSidebar.css">
    <link rel="stylesheet" href="../assets/style/agency/agencyEdit.css">
    <script src="../assets/script/agencyEdit.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <?php require_once('./components/agencyHeader.php'); ?>

    <!-- エージェンシー情報登録フォーム -->
    <main>
        <div class="craftContainer">
            <section class="craft-inner">
                <p class="sidebar-wel">ようこそ！！</p>
                <p class="sidebar-name"><?= $Agency[0]['agency_name']; ?></p>
                <div class="craft-sidebar">
                    <ul class="craft-sidebarContainer">
                        <li><a href="./index.php" class="craft-sidebarItem">学生情報</a></li>
                        <li><a href="./editorSignup.php" class="craft-sidebarItem">ユーザー登録</a></li>
                        <li><a href="agencyEdit.php" class="craft-sidebarItem current">エージェンシー情報編集</a></li>
                    </ul>
                </div>
            </section>
            <section class="agency-regi">
                <div class="agency-regiContainer">
                    <div class="agency-regiTitle">
                        <p>エージェンシー情報編集</p>
                    </div>
                    <form action="agencyEdit.php" method="POST" enctype="multipart/form-data" id="agencyEditForm">
                        <div class="agency-regiForm">
                            <?php foreach ($agencies as $i => $agency) { ?>
                                <div class="agency-regiFormContainer">
                                    <div class="agency-regiFormInner">
                                        <label class="agency-regiFormText">企業名</label>
                                        <input type="text" name="companyName" class="agency-regiFormInput agency-regiFormName" value="<?= $agency["agency_name"]; ?>" required>
                                    </div>
                                    <div class="agency-regiFormInner">
                                        <label for="question" class="form-label">企業ロゴ</label>
                                        <input type="file" name="image" id="image" class="agency-regiFormInput form-control required" accept=".png, .jpg, .jpeg, .gif">
                                    </div>
                                    <div class="agency-regiFormInner">
                                        <label>企業の強み</label>
                                        <input type="text" name="companyStrength" class="agency-regiFormInput agency-regiFormStrength" value="<?= $agency["strong_point"]; ?>" required>
                                    </div>
                                </div>

                                <div class="agency-regiFormInner2">
                                    <ul class="narrow-List">
                                        <input type="hidden" value="" name="feature_list">
                                        <?php foreach ($category as $catKey => $catItem) { ?>
                                            <li class="narrow-Item">
                                                <p class="narrow-eachTitle"><?= $catItem["category_name"]; ?></p>
                                                <div class="narrow-eachList">
                                                    <?php foreach ($feature_info as $featKey => $featItem) {
                                                        if ($featItem["category_id"] == $catItem["id"]) {
                                                            // featureテーブルのstatusが1の場合はチェックボックスにchecked属性を追加
                                                            $isChecked = $agency["feature"][$featKey]["status"] == 1 ? 'checked' : '';
                                                    ?>
                                                            <input name="feature<?= $featKey + 1; ?>" type="checkbox" id="feature<?= $featKey; ?>" <?= $isChecked ?>>
                                                            <label for="feature<?= $featKey; ?>" class="featureTitle"><?= $featItem["feature_name"]; ?></label>
                                                    <?php }
                                                    } ?>
                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>

                                <div class="agency-regiFormContainer2">
                                    <?php foreach ($levels_info as $t => $level_info) { ?>
                                        <div class="agency-rangeItem">
                                            <p><?= $agency["levels"][$t]["levels_min"]; ?></p>
                                            <div class="agency-regiFormInner3">
                                                <label><?= $agency["levels"][$t]["levels_name"]; ?></label>
                                                <input type="range" name="level<?= $t + 1; ?>" min="1" max="5" class="agency-regiFormInput agency-regiFormRange" list="my-datalist<?= $t; ?>" value="<?= $agency["levels"][$t]["level"]; ?>">
                                                <datalist id="my-datalist<?= $t; ?>">
                                                    <option value="1">
                                                    <option value="2">
                                                    <option value="3">
                                                    <option value="4">
                                                    <option value="5">
                                                </datalist>
                                            </div>
                                            <p><?= $agency["levels"][$t]["levels_max"]; ?></p>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="agency-regiFormContainer">
                                    <div class="agency-regiFormInner">
                                        <label>住所</label>
                                        <input type="text" name="address" class="agency-regiFormInput agency-regiFormAddress" value="<?= $agency["address"]; ?>" required>
                                    </div>
                                    <div class="agency-regiFormInner">
                                        <label>対応可能時間</label>
                                        <input type="text" name="time" class="agency-regiFormInput agency-regiFormTime" value="<?= $agency["compatible_time"]; ?>" required>
                                    </div>
                                    <div class="agency-regiFormInner">
                                        <label>詳細情報</label>
                                        <input type="text" name="free" class="agency-regiFormInput agency-regiFormDetail" value="<?= $agency["supplement"]; ?>" required>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>

                        <div class="agency-regiFormSubmit1">
                            <div class="agency-regiFormSubmit">
                                <button class="agency-regiFormSubmitButton" type="submit" id="registerButton">登録</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>

</body>

</html>

<?php
if (isset($message)) {
    echo ($message);
}  ?>

<?php
// echo '<pre>';
// var_dump($_SESSION["agencyID"]);
// echo '</pre>';

?>