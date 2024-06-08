document.querySelectorAll('.hero-infoBtn').forEach(button => {
    button.addEventListener('click', function() {
        // console.log('詳細ボタンがクリックされました');
        const popup = this.closest('.hero-item').querySelector('.splide-popup');
        popup.classList.remove('splide-hidden');
        // const detailButton = this.closest('.hero-item').querySelector('.button');
        // detailButton.classList.add('popup-opened');
        // 詳細ポップアップが表示されている場合のみ、詳細ボタンを非表示にする
        if (!popup.classList.contains('splide-hidden')) {
            this.style.display = 'none';
        }
    });
});

document.querySelectorAll('.splide-closeButton').forEach(button => {
    button.addEventListener('click', function() {
        // console.log('閉じるボタンがクリックされました');
        const keepButton = this.closest('.hero-item').querySelector('.button');
        const splidePopup=this.closest('.splide-popup').classList.add('splide-hidden');
        // 閉じるボタンがクリックされたら、詳細ボタンを再度表示する
        const detailButton = this.closest('.hero-item').querySelector('.hero-infoBtn');
        detailButton.style.display = 'inline-block';
    });
});