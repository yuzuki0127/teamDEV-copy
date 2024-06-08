"use strict";

// ゆづき

//splideの導入
const slideOptions = {
    type: "loop",
    gap: "3em",
    padding: "10rem",
    pagination: true,
    autoScroll: {
        speed: 2,
    }, 
    breakpoints: {
        768: {
            perPage: 1,
            padding: "1rem",
            width: "100%",
            pagination: true,
        }
    }
};

document.addEventListener("DOMContentLoaded", function () {
    const slider = new Splide(".splide", slideOptions).mount();
});

//絞り込み

let narrowList = [];

// ページが読み込まれたときにlocalStorageからnarrowListを取得する
window.addEventListener("load", function () {
    const storedNarrowList = localStorage.getItem("narrowList");
    if (storedNarrowList) {
        narrowList = JSON.parse(storedNarrowList);
        updateNarrowButtons();
    }
});

const narrowBtn = document.querySelectorAll(".narrow-eachBtn");
const clearBtn = document.querySelector(".narrow-clearBtn");

narrowBtn.forEach(button => {
    button.addEventListener("click", narrowButton);
});

clearBtn.addEventListener("click", clearNarrow);

function narrowButton() {
    this.classList.toggle('narrow-NOTeachBtn');
    updateNarrowList();
}

function updateNarrowList() {
    narrowList = [];
    const narrowBtnsWithoutClass = document.querySelectorAll(".narrow-eachBtn:not(.narrow-NOTeachBtn)");
    narrowBtnsWithoutClass.forEach(button => {
        // narrowList.push(button.id);
        narrowList.push(button.dataset.narrow);
        // console.log(button.dataset.narrow);
    });
    localStorage.setItem("narrowList", JSON.stringify(narrowList));
}

function updateNarrowButtons() {
    narrowBtn.forEach(button => {
        // if (narrowList.includes(button.id)) {
        if (narrowList.includes(button.dataset.narrow)) {
            button.classList.remove("narrow-NOTeachBtn");
        } else {
            button.classList.add("narrow-NOTeachBtn");
        }
    });
}

function clearNarrow() {
    narrowBtn.forEach(button => {
        button.classList.add("narrow-NOTeachBtn");
    });
    narrowList = [];
    localStorage.removeItem("narrowList");
}

const narrowSearch = document.querySelector("#narrow-search");

narrowSearch.addEventListener("click", narrowSearchButton);

function narrowSearchButton() {
    updateNarrowList();
    // 配列をhiddenフィールドの値に設定
    document.getElementById("narrowListInput").value = JSON.stringify(narrowList);
    // フォームを送信
    document.getElementById("myForm").submit();
}


// document.addEventListener("DOMContentLoaded", function () {
//     const keepButtons = document.querySelectorAll(".agency-keepButton");

//     keepButtons.forEach(button => {
//         button.addEventListener("submit", async function (event) {
//             event.preventDefault(); // デフォルトのフォーム送信を停止

//             const formData = new FormData(this); // フォームデータを取得

//             try {
//                 const response = await fetch(this.action, {
//                     method: this.method,
//                     body: formData
//                 });
            
//                 if (!response.ok) {
//                     const errorMessage = await response.text(); // HTML形式のエラーメッセージを取得
//                     throw new Error(errorMessage); // エラーメッセージを含んだErrorオブジェクトをスロー
//                 }
            
//                 const data = await response.json(); // サーバーからの応答をJSONとして解析
            
//                 // キープリストへの追加が成功した場合
//                 if (data.success) {
//                     // キープリストの更新などの追加の処理を行う
//                     alert("キープリストに追加しました！");
//                 } else {
//                     alert("キープリストへの追加に失敗しました。。。");
//                 }
//             } catch (error) {
//                 console.error('There has been a problem with your fetch operation:', error);
//                 alert("エラーが発生しました。詳細はコンソールを確認してください。");
//             }
//         });
//     });
// });


