//ハンバーガーメニューのクリック処理
//ヘッダー・ボタンの要素取得
const $header = document.getElementById('js-header');
const $button = document.getElementById('js-headerButton');
//ボタンをクリックしたときの処理
$button.addEventListener('click', function () {
    $header.classList.toggle('is-open');
});