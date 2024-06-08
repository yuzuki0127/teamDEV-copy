<?php
require_once('./dbconnect.php');
session_start();

// フォームが送信されたかどうかを確認
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $dbh->beginTransaction();

        // user_infoテーブル
        // POSTされたデータを取得
        $name = $_POST['name'];
        $sex = $_POST['gender'];
        $graduate_year = $_POST['graduateYear'];
        $university = $_POST['university'];
        $context_of_use = $_POST['contextOfUse'];
        $faculty = $_POST['faculty'];
        $email = $_POST['email'];
        $phone = $_POST['phoneNumber'];
        $supplement = $_POST['free'];

        // SQL文を準備
        $stmt = $dbh->prepare("INSERT INTO user_info (name, sex, graduate_year, university, selection, faculty, email, phone, supplement)
        VALUES (:name, :sex, :graduate_year, :university, :selection, :faculty, :email, :phone, :supplement)");
        // パラメータをバインド
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':sex', $sex);
        $stmt->bindParam(':graduate_year', $graduate_year);
        $stmt->bindParam(':university', $university);
        $stmt->bindParam(':selection', $context_of_use);
        $stmt->bindParam(':faculty', $faculty);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':supplement', $supplement);
        // SQLを実行
        $stmt->execute();

        // 直前に挿入された行のIDを取得
        $user_id = $dbh->lastInsertId();

        // userテーブル

        // $_SESSION['apply_list']の値を一つずつ取り出して$agency_idに追加
        foreach ($_SESSION['apply_list'] as $agency_id) {
            $stmt = $dbh->prepare("INSERT INTO user (agency_id, user_id) VALUES (:agency_id, :user_id)");
            $stmt->bindParam(':agency_id', $agency_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
        }

        $dbh->commit();

        // JSON形式でレスポンスを返す
        echo json_encode(array('success' => true));
        $_SESSION = array();
        session_destroy();
        exit;
    } catch (PDOException $e) {
        $dbh->rollBack();
        // エラーメッセージをJSON形式で返す
        echo json_encode(array('success' => false, 'message' => '登録に失敗しました。'));
        exit;
    }
}
?>







<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ユーザー応募フォーム</title>
    <link rel="stylesheet" href="./assets/style/reset.css" />
    <link rel="stylesheet" href="./assets/style/components/header.css">
    <link rel="stylesheet" href="./assets/style/sp/header.css">
    <link rel="stylesheet" href="./assets/style/components/userEntryForm.css">
    <script src="./assets/script/userHeader.js" defer></script>
    <script src="./assets/script/userEntryFrom.js" defer></script>
    <!-- <script src="../node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css"> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- ヘッダー -->
    <?php
    require_once('./components/header.php'); ?>

    <main>
        <section class="user-entry">
            <div class="user-entryContainer">
                <div class="user-entryTitle">
                    <p>応募フォーム</p>
                </div>
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="user-entryForm">
                        <div class="user-entryFormContainer">
                            <div class="user-entryFormInner">
                                <label class="user-entryFormText">名前</label>
                                <input type="text" name="name" class="user-entryFormInput user-entryFormName" required>
                            </div>
                            <div class="user-entryFormInner">
                                <label>性別</label>
                                <div class="user-entryFormGenderContainer">
                                    <input type="radio" name="gender" value="1" class="user-entryFormInput user-entryFormRadio1">男
                                    <input type="radio" name="gender" value="2" class="user-entryFormInput user-entryFormRadio1">女
                                </div>
                            </div>
                            <div class="user-entryFormInner">
                                <label>文理</label>
                                <div class="user-entryFormContextOfUseContainer">
                                    <div><input type="radio" name="contextOfUse" value="1" class="user-entryFormInput user-entryFormRadio2">文系</div>
                                    <div><input type="radio" name="contextOfUse" value="2" class="user-entryFormInput user-entryFormRadio2">理系</div>
                                </div>
                            </div>
                            <div class="user-entryFormInner">
                                <label>卒業年度</label>
                                <input type="number" name="graduateYear" class="user-entryFormInput user-entryFormGraduate" required>
                            </div>
                            <div class="user-entryFormInner">
                                <label>大学</label>
                                <input type="text" name="university" class="user-entryFormInput user-entryFormUniversity" required>
                            </div>
                            <div class="user-entryFormInner">
                                <label>学部</label>
                                <input type="text" name="faculty" class="user-entryFormInput user-entryFormFaculty" required>
                            </div>
                            <div class="user-entryFormInner">
                                <label>メールアドレス</label>
                                <input type="email" name="email" class="user-entryFormInput user-entryFormEmail" required>
                            </div>
                            <div class="user-entryFormInner">
                                <label>電話番号</label>
                                <input type="tel" name="phoneNumber" class="user-entryFormInput user-entryFormPhoneNumber" required>
                            </div>
                            <div class="user-entryFormInner">
                                <label>備考</label>
                                <input type="text" name="free" class="user-entryFormInput user-entryFormFree">
                            </div>
                        </div>
                    </div>
                    <div class="user-entryFormSubmit1">
                        <div class="user-entryFormSubmit">
                            <input type="submit" class="user-entryFormSubmitButton" value="送信"></input>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
</body>

<?php
// echo '<pre>';
// var_dump($_SESSION['apply_list']);
// echo '</pre>';
?>