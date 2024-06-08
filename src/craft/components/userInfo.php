<div class="user-informationPanel">
    <ul class="tab__panel-box">
        <?php foreach ($user_info as $i => $user) { ?>
            <li class="person-information">
                <div class="user-informationUserName">
                    <p><?= $user["name"]; ?></p>
                </div>
                <div class="user-informationGender">
                    <p><?php if ($user["selection"] == 0) {
                            echo ("理系");
                        } else {
                            echo ("文系");
                        } ?></p>
                </div>
                <div class="user-informationPhoneNumber">
                    <p><?= $user["phone"]; ?></p>
                </div>
                <div class="user-informationMail">
                    <p><?= $user["graduate_year"]; ?>年卒</p>
                </div>
                <div class="user-detail">
                    <button class="user-detailButton">詳細</button>
                </div>
                <div class="popup hidden">
                    <p class="popup-item">大学：<?= $user["university"]; ?></p>
                    <p class="popup-item">学部：<?= $user["faculty"]; ?></p>
                    <p class="popup-item">備考：<?= $user["supplement"]; ?></p>
                    <button class="closeButton"></button>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>