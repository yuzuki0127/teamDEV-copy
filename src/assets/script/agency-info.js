document.querySelectorAll('.agency-infoBtn').forEach(button => {
    button.addEventListener('click', function() {
        console.log('詳細ボタンがクリックされました');
        const popup = this.closest('.agency-infoItem').querySelector('.popup');
        popup.classList.remove('hidden');
        const keepButton = this.closest('.agency-infoItem').querySelector('.open-button');
        keepButton.classList.add('popup-opened');
        // 詳細ポップアップが表示されている場合のみ、詳細ボタンを非表示にする
        if (!popup.classList.contains('hidden')) {
            this.style.display = 'none';
        }
    });
});

document.querySelectorAll('.closeButton').forEach(button => {
    button.addEventListener('click', function() {
        // console.log('閉じるボタンがクリックされました');
        const keepButton = this.closest('.agency-infoItem').querySelector('.open-button');
        this.closest('.popup').classList.add('hidden');
        keepButton.classList.remove('popup-opened');
        // 閉じるボタンがクリックされたら、詳細ボタンを再度表示する
        const detailButton = this.closest('.agency-infoItem').querySelector('.agency-infoBtn');
        detailButton.style.display = 'inline-block';
    });
});
