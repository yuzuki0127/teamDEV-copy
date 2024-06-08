document.addEventListener("DOMContentLoaded", function () {
    const registrationForm = document.getElementById("registrationForm");

    registrationForm.addEventListener("submit", async function (event) {
        event.preventDefault();

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger",
            },
        });

        swalWithBootstrapButtons
            .fire({
                title: "この内容で登録しますか?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "はい",
                cancelButtonText: "いいえ",
                reverseButtons: true,
            })
            .then(async (result) => {
                if (result.isConfirmed) {
                    // フォームデータを取得
                    const formData = new FormData(registrationForm);
                    try {
                        // サーバーにデータを送信
                        const response = await fetch("agencyRegister.php", {
                            method: "POST",
                            body: formData,
                        });
                        if (!response.ok) {
                            swalWithBootstrapButtons.fire({
                                icon: "error",
                                title: "登録に失敗しました",
                            });
                        }else {
                            swalWithBootstrapButtons
                                .fire({
                                    icon: "success",
                                    title: "登録に成功しました",
                                    text: "ご登録ありがとうございます。登録確認メールをお送りしましたのでお確かめください",
                                })
                                .then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href =
                                            "http://localhost:8080/agency/index.php"; // 成功した場合にリダイレクト
                                    }
                                });
                        }
                    } catch (error) {
                        console.error("Error:", error);
                        swalWithBootstrapButtons.fire({
                            icon: "error",
                            title: "登録に失敗しました",
                            text: "エラーが発生しました。詳細はコンソールを確認してください。",
                        });
                    }
                }
            });
    });
});
