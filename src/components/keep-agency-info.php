<ul class="agency-infoList">
    <?php foreach ($agencies as $agency) {
        if (in_array($agency["id"], $_SESSION['keep_list'])) { ?>
            <li class="agency-infoItem">
                <form method="POST" action="keep.php" class="checkboxList">
                    <input type="checkbox" name="apply[]" class="keep-check" id="checkbox<?= $agency["id"]; ?>" value="<?= $agency["id"]; ?>" checked>
                    <label for="checkbox<?= $agency["id"]; ?>" class="agency-checkBox">チェックしてまとめて応募</label>
                </form>
                <div class="agency-easyDesc">
                    <figure class="agency-infoImg">
                        <img src="../assets/img/agencies/<?= $agency["image"]; ?>" alt="" />
                    </figure>
                    <div class="agency-descItem">
                        <p class="agency-Name"><?= $agency["agency_name"]; ?></p>
                        <div class="agency-infoButton">
                            <button class="agency-infoBtn">詳細</button>
                        </div>
                        <p class="agency-firstDesc"><?= $agency["strong_point"]; ?></p>
                    </div>
                </div>
                <ul class="agency-featureList">
                    <?php
                    foreach ($agency["feature"] as $feature) {
                        if ($feature["status"] == 1) { ?>
                            <li class="agency-featureItem narrow<?= $feature["feature_id"]; ?>"><?= $feature["feature_name"]; ?></li>
                    <?php }
                    } ?>
                </ul>
                <?php foreach ($agency["levels"] as $level) { ?>
                    <ul class="agency-5stepList">
                        <li class="agency-5stepDesc">
                            <p><?= $level["levels_name"]; ?></p>
                        </li>
                        <li>
                            <ul class="agency-5stepItem">
                                <li class="agency-5stepDesc">
                                    <p><?= $level["levels_min"]; ?></p>
                                </li>
                                <li>
                                    <figure>
                                        <?php for ($l = 0; $l < 6; $l++) {
                                            if ($level["level"] == $l + 1) { ?>
                                                <img src="../assets/img/5step-No<?= $l + 1; ?>.png" alt="" />
                                        <?php }
                                        } ?>
                                    </figure>
                                </li>
                                <li class="agency-5stepDesc">
                                    <p><?= $level["levels_max"]; ?></p>
                                </li>
                            </ul>
                        </li>
                    </ul>
                <?php } ?>
                <form action="keep.php" method="POST" class="agency-selectButton">
                    <input type="hidden" name="agency_id" value="<?= $agency["id"]; ?>">
                    <input class="agency-rejectBtn" type="submit" value="削除">
                </form>
                <div class="popup hidden">
                <p class="popup-item">住所：<?=$agency["address"];?></p>
                <p class="popup-item">対応可能時間：<?=$agency["compatible_time"];?></p>
                <p class="popup-item">備考：<?=$agency["supplement"];?></p>
                    <button class="closeButton"></button>
                </div>
            </li>
    <?php }
    } ?>
</ul>