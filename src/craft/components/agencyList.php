<?php
$agencies = $dbh->query('SELECT * FROM agency WHERE approval = 1')->fetchAll(PDO::FETCH_ASSOC);
$features = $dbh->query("SELECT * FROM feature INNER JOIN feature_info ON feature.feature_id = feature_info.id;")->fetchAll(PDO::FETCH_ASSOC);
$feature_info = $dbh->query("SELECT * FROM feature_info")->fetchAll(PDO::FETCH_ASSOC);
$levels = $dbh->query("SELECT * FROM levels INNER JOIN levels_info ON levels.levels_id = levels_info.id;")->fetchAll(PDO::FETCH_ASSOC);
$levels_info = $dbh->query("SELECT * FROM levels_info")->fetchAll(PDO::FETCH_ASSOC);

foreach ($agencies as $aKey => $agency) {
    $agency["feature"] = [];
    foreach ($features as $fKey => $feature) {
        if ($feature["agency_id"] == $agency["id"]) {
            $agency["feature"][] = $feature;
        }
    }
    $agencies[$aKey] = $agency;
}
foreach ($agencies as $aKey => $agency) {
    $agency["levels"] = [];
    foreach ($levels as $lKey => $level) {
        if ($level["agency_id"] == $agency["id"]) {
            $agency["levels"][] = $level;
        }
    }
    $agencies[$aKey] = $agency;
}

?>

<section class="agency-inner">
    <p class="agency-infoTitle">エージェンシー情報</p>
    <ul class="agency-infoList">

        <?php foreach ($agencies as $i => $agency) { ?>
            <li class="agency-infoItem">
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
                    <?php foreach ($feature_info as $feature) {
                        foreach ($agency["feature"] as $fKey => $agency_feature) {
                            if ($agency_feature["status"] == 1 && $agency_feature["feature_id"] == $feature["id"]) { ?>
                                <li class="agency-featureItem narrow<?= $agency_feature["feature_id"]; ?>"><?= $agency_feature["feature_name"]; ?></li>
                    <?php   }
                        }
                    } ?>
                </ul>

                <?php foreach ($levels_info as $t => $level_info) { ?>
                    <ul class="agency-5stepList">
                        <li class="agency-5stepDesc">
                            <p><?= $agency["levels"][$t]["levels_name"]; ?></p>
                        </li>
                        <li>
                            <ul class="agency-5stepItem">
                                <li class="agency-5stepDesc">
                                    <p><?= $agency["levels"][$t]["levels_min"]; ?></p>
                                </li>
                                <li>
                                    <figure>
                                        <?php for ($l = 0; $l < 6; $l++) {
                                            if ($agency["levels"][$t]["level"] == $l + 1) { ?>
                                                <img src="../assets/img/5step-No<?= $l + 1; ?>.png" alt="" />
                                        <?php }
                                        } ?>
                                    </figure>
                                </li>
                                <li class="agency-5stepDesc">
                                    <p><?= $agency["levels"][$t]["levels_max"]; ?></p>
                                </li>
                            </ul>
                        </li>
                    </ul>
                <?php } ?>

                <form action="craftAgency.php" method="POST" class="agency-selectButton open-button">
                    <input class="agency-rejectBtn" type="submit" name="<?= $agency["id"]; ?>" value="削除">
                </form>
                <div class="popup hidden">
                <p class="popup-item">住所：<?=$agency["address"];?></p>
                <p class="popup-item">対応可能時間：<?=$agency["compatible_time"];?></p>
                <p class="popup-item">備考：<?=$agency["supplement"];?></p>
                    <button class="closeButton"></button>
                </div>
            </li>
        <?php } ?>

    </ul>
</section>