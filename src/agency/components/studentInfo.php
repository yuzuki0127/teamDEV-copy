<div class="user-informationPanel">
    <ul class="tab__panel-box">
        <?php foreach ($users as $user) : ?>
            <?php
            $stmt = $dbh->prepare('SELECT * FROM user_info WHERE id = :id');
            $stmt->execute(['id' => $user['user_id']]);
            $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

            $selected_year = isset($_POST['graduate']) ? $_POST['graduate'] : "NoYear";
            $selected_context = isset($_POST['ContextOfUse']) ? $_POST['ContextOfUse'] : "NoSelect";
            $show = true;
            if ($selected_year !== "NoYear" && $user_info['graduate_year'] != $selected_year) {
                $show = false;
            }
            if ($selected_context !== "NoSelect" && $user_info['selection'] != ($selected_context == "lit" ? 1 : 0)) {
                $show = false;
            }
            if ($show) :
            ?>
                <li class="person-information">
                    <div class="user-informationUserName">
                        <p><?= $user_info["name"]; ?></p>
                    </div>
                    <div class="user-informationGender">
                        <p><?= $user_info["selection"] == 0 ? '理系' : '文系'; ?></p>
                    </div>
                    <div class="user-informationPhoneNumber">
                        <p><?= $user_info["phone"]; ?></p>
                    </div>
                    <div class="user-informationMail">
                        <p><?= $user_info["graduate_year"]; ?>年卒</p>
                    </div>
                    <div class="user-detail">
                        <button class="user-detailButton">詳細</button>
                    </div>
                    <div class="popup hidden">
                <p class="popup-item">大学：<?=$user_info["university"];?></p>
                <p class="popup-item">学部：<?=$user_info["faculty"];?></p>
                <p class="popup-item">備考：<?=$user_info["supplement"];?></p>
                    <button class="closeButton"></button>
                </div>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>