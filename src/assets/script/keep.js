// let keepList = localStorage.getItem('keepList') ? JSON.parse(localStorage.getItem('keepList')) : [];

// console.log(keepList);


// //後
// document.addEventListener("DOMContentLoaded", function() {
//     const rejectForms = document.querySelectorAll(".agency-selectButton");

//     rejectForms.forEach(form => {
//         form.addEventListener("submit", function(event) {
//             event.preventDefault(); // デフォルトのフォーム送信を防止

//             const agencyId = this.querySelector(".agency-rejectBtn").dataset.agencyid;

//             // agencyIdに対応するボタンIDをkeepListから削除
//             const index = keepList.indexOf(agencyId);
//             if (index !== -1) {
//                 keepList.splice(index, 1);
//                 // localStorageにkeepListを保存
//                 localStorage.setItem('keepList', JSON.stringify(keepList));
//             }

//             // 送信するデータを準備
//             const formData = new FormData();
//             formData.append('keepList', JSON.stringify(keepList));

//             // フォームの送信
//             fetch(this.action, {
//                 method: 'POST',
//                 body: formData
//             })
//             .then(response => {
//                 if (!response.ok) {
//                     throw new Error('Network response was not ok');
//                 }
//                 return response.text();
//             })
//             .then(data => {
//                 console.log(data); // レスポンスをログに出力
//             })
//             .catch(error => {
//                 console.error('There has been a problem with your fetch operation:', error);
//             });
//         });
//     });
// });





// console.log(JSON.stringify({ keepList: keepList }));

// // 応募ボタン（localstrage削除）
// const keepSubmitBtn = document.querySelector("#keep-submitBtn");
// keepSubmitBtn.addEventListener("click", function () {
//     localStorage.removeItem('keepList');
//     const keepList = localStorage.getItem('keepList');
//     console.log(keepList);
// });



window.addEventListener('DOMContentLoaded', () => {
    const applyForm = document.getElementById('applyForm');

    const checkboxes = document.querySelectorAll('.keep-check'); // チェックボックスを取得
    const popupNumber = document.querySelector('.popup-number'); // 選択件数を表示する要素

    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('.keep-check:checked').length; // チェックされた項目の数を取得
        popupNumber.textContent = selectedCount + "/" + checkboxes.length + "件"; // 選択件数を表示
    }

    // チェックボックスの状態が変化したときに選択件数を更新
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // ページ読み込み時に初期の選択件数を表示
    updateSelectedCount();

    applyForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const selectedAgencies = document.querySelectorAll('.keep-check:checked');
        const numSelectedAgencies = selectedAgencies.length;

        if (numSelectedAgencies === 0) {
            Swal.fire({
                icon: 'error',
                title: 'エラー',
                text: 'エージェンシーが選択されていません。エージェンシーを選択してください。',
                confirmButtonText: 'OK'
            });
        } else if (numSelectedAgencies === 1) {
            Swal.fire({
                icon: 'warning',
                title: '注意',
                text: '選択されたエージェンシーが1つのみです。複数応募することが推奨されています。本当に応募しますか？',
                showCancelButton: true,
                confirmButtonText: 'はい',
                cancelButtonText: 'もっと選ぶ'
            }).then((result) => {
                if (result.isConfirmed) {
                    // チェックされたエージェンシーのIDを配列にしてフォームに追加
                    const selectedAgenciesArray = Array.from(selectedAgencies).map(checkbox => checkbox.value);
                    document.getElementById('selectedAgencies').value = JSON.stringify(selectedAgenciesArray);
                    applyForm.submit();
                }
            });
        } else {
            // チェックされたエージェンシーのIDを配列にしてフォームに追加
            const selectedAgenciesArray = Array.from(selectedAgencies).map(checkbox => checkbox.value);
            document.getElementById('selectedAgencies').value = JSON.stringify(selectedAgenciesArray);
            applyForm.submit();
        }
    });
});
