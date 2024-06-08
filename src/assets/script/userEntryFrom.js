document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", async function (event) {
        event.preventDefault(); // フォームのデフォルトの送信を停止

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
        });

        swalWithBootstrapButtons.fire({
            title: "この内容で応募しますか?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "はい",
            cancelButtonText: "いいえ",
            reverseButtons: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                const formData = new FormData(this); // フォームデータを取得

                try {
                    const response = await fetch(this.action, {
                        method: this.method,
                        body: formData
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json(); // サーバーからの応答をJSONとして解析

                    // 応答を処理する（例：成功時の処理）
                    if (data.success) {
                        swalWithBootstrapButtons.fire({
                            icon: 'success',
                            title: '登録に成功しました',
                            text: 'ご応募ありがとうございます。応募確認メールをお送りしましたのでお確かめください',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "http://localhost:8080/index.php" // 成功した場合にリダイレクト
                            }
                        });
                    } else {
                        swalWithBootstrapButtons.fire({
                            icon: 'error',
                            title: '登録に失敗しました',
                        });
                    }
                } catch (error) {
                    console.error('There has been a problem with your fetch operation:', error);
                    swalWithBootstrapButtons.fire({
                        icon: 'error',
                        title: '登録に失敗しました',
                        text: 'エラーが発生しました。詳細はコンソールを確認してください。',
                    });
                }
            }
        });
    });
});
