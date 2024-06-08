document.addEventListener('DOMContentLoaded', function () {
    // 拒否ボタンがクリックされた時の処理
    document.querySelectorAll('.agency-rejectBtn').forEach(function(button) {
        button.addEventListener('click', async function() {
            const agencyId = this.getAttribute('data-agency-id');
            const confirmReject = await confirmAction('本当に拒否しますか？');
            if (confirmReject) {
                rejectAgency(agencyId);
            }
        });
    });

    // 承認ボタンがクリックされた時の処理
    document.querySelectorAll('.agency-approvalBtn').forEach(function(button) {
        button.addEventListener('click', async function() {
            const agencyId = this.getAttribute('data-agency-id');
            const confirmApproval = await confirmAction('本当に承認しますか？');
            if (confirmApproval) {
                approveAgency(agencyId);
            }
        });
    });
});

// SweetAlert2で確認メッセージを表示する関数
async function confirmAction(message) {
    const { value: confirm } = await Swal.fire({
        title: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'はい',
        cancelButtonText: 'キャンセル'
    });
    return confirm;
}

// 拒否処理のAjaxリクエストを送信する関数
async function rejectAgency(agencyId) {
    const response = await fetch('reject.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `agency_id=${agencyId}`
    });
    if (response.ok) {
        window.location.reload();
    }
}

// 承認処理のAjaxリクエストを送信する関数
async function approveAgency(agencyId) {
    const response = await fetch('approve.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `agency_id=${agencyId}`
    });
    if (response.ok) {
        window.location.reload();
    }
}
