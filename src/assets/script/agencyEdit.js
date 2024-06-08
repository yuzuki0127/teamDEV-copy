document.addEventListener("DOMContentLoaded", function () {
    const agencyEditForm = document.getElementById("agencyEditForm");

    agencyEditForm.addEventListener("submit", async function (event) {
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
                    const formData = new FormData(agencyEditForm);
                    try {
                        // サーバーにデータを送信
                        const response = await fetch("agencyEdit.php", {
                            method: "POST",
                            body: formData,
                        });
                        if (!response.ok) {
                            swalWithBootstrapButtons.fire({
                                icon: "error",
                                title: "更新に失敗しました",
                            });
                        } else {
                            swalWithBootstrapButtons
                                .fire({
                                    icon: "success",
                                    title: "更新に成功しました",
                                    text: "更新が完了しました。",
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
                            title: "更新に失敗しました",
                            text: "エラーが発生しました。詳細はコンソールを確認してください。",
                        });
                    }
                }
            });
    });
});
