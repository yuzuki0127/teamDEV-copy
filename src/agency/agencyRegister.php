<?php
require_once('../dbconnect.php');
require_once('../vendor/autoload.php');

use Verot\Upload\Upload;

session_start();

$agencies = $dbh->query('SELECT * FROM agency')->fetchAll(PDO::FETCH_ASSOC);
$features = $dbh->query("SELECT * FROM feature INNER JOIN feature_info ON feature.feature_id = feature_info.id;")->fetchAll(PDO::FETCH_ASSOC);
$feature_info = $dbh->query("SELECT * FROM feature_info")->fetchAll(PDO::FETCH_ASSOC);
$levels = $dbh->query("SELECT * FROM levels INNER JOIN levels_info ON levels.levels_id = levels_info.id;")->fetchAll(PDO::FETCH_ASSOC);
$levels_info = $dbh->query("SELECT * FROM levels_info")->fetchAll(PDO::FETCH_ASSOC);
$editors = $dbh->query("SELECT * FROM editor INNER JOIN editor_info ON editor.editor_id = editor_info.id;")->fetchAll(PDO::FETCH_ASSOC);
$editor_info = $dbh->query("SELECT * FROM editor_info")->fetchAll(PDO::FETCH_ASSOC);
$edit = $dbh->query("SELECT * FROM editor")->fetchAll(PDO::FETCH_ASSOC);
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
foreach ($agencies as $aKey => $agency) {
    $agency["editor"] = [];
    foreach ($editors as $lKey => $editor) {
        if ($editor["agency_id"] == $agency["id"]) {
            $agency["editor"][] = $editor;
        }
    }
    $agencies[$aKey] = $agency;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $editorName = $_POST['editorName'];
    $editorEmail = $_POST['editorEmail'];
    $password = $_POST['password'];
    $rePassword = $_POST['rePassword'];

    // エディター情報を取得
    $editorName = $_POST['editorName'];
    $editorEmail = $_POST['editorEmail'];
    $password = $_POST['password'];

    // エージェンシー情報を取得
    $companyName = $_POST['companyName'];
    $companyStrength = $_POST['companyStrength'];
    $address = $_POST['address'];
    $compatibleTime = $_POST['time'];
    $supplement = $_POST['free'];

    // 現在時刻を取得
    $createdAt = date("Y-m-d");

    // エージェンシーの承認ステータス
    $approval = 0;

    try {
        $dbh->beginTransaction();

        // パスワードと確認用パスワードの一致を確認する
        if ($password == $rePassword) {

            // パスワードのハッシュ化
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

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

            // //ファイル動かないとき用
            // $image_name = "";

            // エディター情報を挿入
            $editorInsertQuery = "INSERT INTO editor_info (editor_name, email, password) VALUES (?, ?, ?)";
            $editorStatement = $dbh->prepare($editorInsertQuery);
            $editorStatement->execute([$editorName, $editorEmail, $hashedPassword]);

            // 挿入されたエディターのIDを取得
            $editorId = $dbh->lastInsertId();

            // エージェンシー情報を挿入
            $agencyInsertQuery = "INSERT INTO agency (agency_name, strong_point, image, compatible_time, address, supplement, created_at, approval) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $agencyStatement = $dbh->prepare($agencyInsertQuery);
            $agencyStatement->execute([$companyName, $companyStrength, $image_name, $compatibleTime, $address, $supplement, $createdAt, $approval]);

            // 挿入されたエージェンシーのIDを取得
            $agencyId = $dbh->lastInsertId();

            // エディターとエージェンシーを関連付ける
            $editorAgencyInsertQuery = "INSERT INTO editor (agency_id, editor_id) VALUES (?, ?)";
            $editorAgencyStatement = $dbh->prepare($editorAgencyInsertQuery);
            $editorAgencyStatement->execute([$agencyId, $editorId]);

            // levelsテーブルに挿入する処理
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'level') === 0) {
                    $levelsId = substr($key, 5); // level の部分を削除して levels_id を取得
                    $levelInsertQuery = "INSERT INTO levels (agency_id, levels_id, level) VALUES (?, ?, ?)";
                    $levelInsertStmt = $dbh->prepare($levelInsertQuery);
                    $levelInsertStmt->execute([$agencyId, $levelsId, $value]);
                }
            }

            // featureテーブルに挿入する処理
            foreach ($feature_info as $featKey => $featItem) {
                $featureValue = isset($_POST['feature' . ($featKey + 1)]) ? 1 : 0;
                $featureInsertQuery = "INSERT INTO feature (agency_id, feature_id, status) VALUES (?, ?, ?)";
                $featureInsertStmt = $dbh->prepare($featureInsertQuery);
                $featureInsertStmt->execute([$agencyId, $featItem['id'], $featureValue]);
            }
        } else {
            $_SESSION['message'] = "パスワードと確認用パスワードが一致しません。";
            header('Location: http://localhost:8080/agency/agencyRegister.php');
            exit;
        }


        $dbh->commit();
        $_SESSION['message'] = "登録に成功しました。";
        $_SESSION['agencyID'] = $agencyId;
        // echo ("登録に成功しました");
        header('Location: http://localhost:8080/agency/index.php');
        exit;
    } catch (PDOException $e) {
        $dbh->rollBack();
        echo ("登録に失敗しました");
        error_log($e->getMessage());
        exit;
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
    <link rel="stylesheet" href="../assets/style/agency/agencyRegister.css">
    <script src="../assets/script/agencyRegister.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <?php require_once('./components/agencyHeader.php'); ?>

    <!-- エージェンシー情報登録フォーム -->
    <main>
        <section class="agency-regi">
            <div class="agency-regiContainer">
                <div class="agency-regiTitle">
                    <p>アカウント作成</p>
                </div>
                <form action="agencyRegister.php" method="POST" enctype="multipart/form-data" id="registrationForm">
                    <div class="agency-regiForm">
                        <div class="agency-regiFormContainer">
                            <div class="agency-regiFormInner">
                                <label class="agency-regiFormText">名前</label>
                                <input type="text" name="editorName" class="agency-regiFormInput agency-regiName" required>
                            </div>
                            <div class="agency-regiFormInner">
                                <label class="agency-regiFormText">メールアドレス</label>
                                <input type="email" name="editorEmail" class="agency-regiFormInput agency-regiFormEmail" required>
                            </div>
                            <div class="agency-regiFormInner">
                                <label class="agency-regiFormText">ログイン用パスワード</label>
                                <input type="password" name="password" class="agency-regiFormInput agency-regiFormPassword" required>
                            </div>
                            <div class="agency-regiFormInner">
                                <label class="agency-regiFormText">ログイン用パスワード(確認用)</label>
                                <input type="password" name="rePassword" class="agency-regiFormInput agency-regiFormRePassword" required>
                            </div>
                            <div class="agency-regiTitle">
                                <p>エージェンシー情報登録</p>
                            </div>
                            <div class="agency-regiFormInner">
                                <label class="agency-regiFormText">企業名</label>
                                <input type="text" name="companyName" class="agency-regiFormInput agency-regiFormName" required>
                            </div>
                            <div class="agency-regiFormInner">
                                <label for="question" class="form-label">企業ロゴ</label>
                                <input type="file" name="image" id="image" class="agency-regiFormInput form-control required" accept=".png, .jpg, .jpeg, .gif">
                            </div>
                            <div class="agency-regiFormInner">
                                <label>企業の強み</label>
                                <input type="text" name="companyStrength" class="agency-regiFormInput agency-regiFormStrength" required>
                            </div>
                        </div>
                        <div class="agency-regiFormInner2">
                            <ul class="narrow-List">
                                <input type="hidden" value="" name="feature_list">
                                <?php foreach ($category as $catKey => $catItem) { ?>
                                    <li class="narrow-Item agency-regiNarrowItem">
                                        <p class="narrow-eachTitle"><?= $catItem["category_name"]; ?></p>
                                        <div class="narrow-eachList agency-regiNarrow">
                                            <?php foreach ($feature_info as $featKey => $featItem) {
                                                if ($featItem["category_id"] == $catItem["id"]) {
                                            ?>
                                                    <input name="feature<?= $featKey + 1; ?>" type="checkbox" id="feature<?= $featKey; ?>">
                                                    <label for="feature<?= $featKey; ?>" class="featureTitle agency-regiFeatureTitle"><?= $featItem["feature_name"]; ?></label>
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
                                        <input type="range" name="level<?= $t + 1; ?>" min="1" max="5" class="agency-regiFormInput agency-regiFormRange" list="my-datalist<?= $t; ?>">
                                        <datalist id="my-datalist<?= $t; ?>" class="regi-datalist">
                                            <option value="1" label="1">
                                            <option value="2" label="2">
                                            <option value="3" label="3">
                                            <option value="4" label="4">
                                            <option value="5" label="5">
                                        </datalist>
                                    </div>
                                    <p><?= $agency["levels"][$t]["levels_max"]; ?></p>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="agency-regiFormContainer">
                            <div class="agency-regiFormInner">
                                <label>住所</label>
                                <input type="text" name="address" class="agency-regiFormInput agency-regiFormAddress" required>
                            </div>
                            <div class="agency-regiFormInner">
                                <label>対応可能時間</label>
                                <input type="text" name="time" class="agency-regiFormInput agency-regiFormTime" required>
                            </div>
                            <div class="agency-regiFormInner">
                                <label>詳細情報</label>
                                <input type="text" name="free" class="agency-regiFormInput agency-regiFormDetail" required>
                            </div>
                        </div>

                    </div>

                    <div class="agency-regiFormSubmit1">
                        <div class="agency-regiFormSubmit">
                            <button class="agency-regiFormSubmitButton" type="submit" id="registerButton">登録</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>

</body>

</html>


<?php
// echo '<pre>';
// var_dump($agencies);
// echo '</pre>';

?>