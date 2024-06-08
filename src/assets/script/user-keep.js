// キープリスト登録（ユーザーホーム上）

// let keepList = localStorage.getItem('keepList') ? JSON.parse(localStorage.getItem('keepList')) : [];

const keepBtn = document.querySelectorAll(".agency-keepBtn");

keepBtn.forEach(button => {
    button.addEventListener("click", function() {
        
        const agencyId = this.getAttribute("data-agencyid");
        
        // ボタンがすでにkeepListに存在するかどうかをチェック
        const index = keepList.indexOf(agencyId);
        
        if (index === -1) {
            keepList.push(agencyId);
            alert("キープリストに登録しました！！");
        }
        
        // // ローカルストレージにkeepListを保存
        // localStorage.setItem('keepList', JSON.stringify(keepList));
        // console.log(JSON.stringify({ keepList: keepList }));
    });
});


// // フォームを取得
// const keepSubmitForm = document.getElementById("keepForm");

// // ボタンを取得
// const keepListButton = document.getElementById("keepList-form");

// // ボタンがクリックされたときの処理
// keepListButton.addEventListener("click", function() {
//     // ローカルストレージからkeepListを取得し、フォームに設定
//     const keepList = localStorage.getItem('keepList') ? JSON.parse(localStorage.getItem('keepList')) : [];
//     document.getElementById('keepListInput').value = JSON.stringify(keepList);
    
//     // フォームを送信
//     keepSubmitForm.submit();
// });
