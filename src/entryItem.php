<?php
require_once('./dbconnect.php');

session_start();

// フォームから送信されたデータがあるか確認
if (isset($_POST['selectedAgencies'])) {
    // JavaScriptから送信されたJSON形式の文字列をデコードして配列に変換
    $selectedAgencies = json_decode($_POST['selectedAgencies']);
    
    // 受け取った配列をセッションに保存
    $_SESSION['apply_list'] = $selectedAgencies;

    // $_SESSION['apply_list']に値が入っている場合かつ1個以上の要素を持つ場合はリダイレクトする
    if (isset($_SESSION['apply_list']) && count($_SESSION['apply_list']) > 0) {
        header("Location: http://localhost:8080/userEntryForm.php");
        exit;
    } else {
        echo "キープリストが選択されていません！！！！！";
    }
} else {
    // データが送信されていない場合の処理
    echo "No data received.";
}
?>