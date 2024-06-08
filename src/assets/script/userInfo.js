document.querySelectorAll('.user-detailButton').forEach(button => {
    button.addEventListener('click', function() {
        console.log('詳細ボタンがクリックされました');
        const popup = this.closest('.person-information').querySelector('.popup');
        if (popup) {
            popup.classList.remove('hidden');
            const keepButton = this.closest('.person-information').querySelector('.user-detailButton');
            keepButton.classList.add('popup-opened');
            // 詳細ポップアップが表示されている場合のみ、詳細ボタンを非表示にする
            this.style.display = 'none';
        }
    });
});

document.querySelectorAll('.closeButton').forEach(button => {
    button.addEventListener('click', function() {
        // console.log('閉じるボタンがクリックされました');
        const popup = this.closest('.popup');
        if (popup) {
            popup.classList.add('hidden');
            const keepButton = this.closest('.person-information').querySelector('.user-detailButton');
            keepButton.style.display = 'inline-block';
        }
    });
});
