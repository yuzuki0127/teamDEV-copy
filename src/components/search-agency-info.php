<?php
$agencies = $dbh->query('SELECT * FROM agency WHERE approval = 1')->fetchAll(PDO::FETCH_ASSOC);
$features = $dbh->query("SELECT * FROM feature INNER JOIN feature_info ON feature.feature_id = feature_info.id;")->fetchAll(PDO::FETCH_ASSOC);
$feature_info = $dbh->query("SELECT * FROM feature_info")->fetchAll(PDO::FETCH_ASSOC);
$levels = $dbh->query("SELECT * FROM levels INNER JOIN levels_info ON levels.levels_id = levels_info.id;")->fetchAll(PDO::FETCH_ASSOC);
$levels_info = $dbh->query("SELECT * FROM levels_info")->fetchAll(PDO::FETCH_ASSOC);



if (isset($_POST['narrowList'])) {
    $narrowList = json_decode($_POST['narrowList']);
    $_SESSION['narrowList'] = $narrowList;
}

// $output = array();
// print_r($_SESSION['narrowList']);

// // 数字とnarrowの対応付け
// for ($i = 0; $i <= count($_SESSION['narrowList']); $i++) {
//     $output["narrow$i"] = $i + 1;
// }
//くりあがってる
//送られたやつをそのまま回してみよう


// $resultArray = array();

// // 配列内の各要素が存在するかどうかを確認し、存在する場合は出力を配列に保存
// foreach ($_SESSION['narrowList'] as $item) {
//     if (isset($output[$item])) {
//         $resultArray[] = $output[$item];
//         $_SESSION['resultArray'] = $resultArray;
//     }
// }

// // エージェンシーのフィルタリング
// $filteredAgencies = [];
// foreach ($agencies as $agency) {
//     $found = false;
//     foreach ($features as $feature) {
//         // feature_id が指定された配列内に含まれているかどうかをチェック
//         if ($feature["agency_id"] == $agency["id"] && in_array($feature["feature_id"], $_SESSION['narrowList']) && $feature["status"] == 1) {
//             $found = true;
//             break;
//         }
//     }
//     if ($found) {
//         $filteredAgencies[] = $agency;
//     }
// }



// フィルタリングと表示
$filteredAgencies = [];
foreach ($agencies as $agency) {
    $found = false;
    foreach ($features as $feature) {
        if ($feature["agency_id"] == $agency["id"] && in_array($feature["feature_id"], $_SESSION['narrowList']) && $feature["status"] == 1) {
            $found = true;
            break;
        }
    }
    if ($found) {
        $filteredAgencies[] = $agency;
    }
}
// エージェンシーごとに含まれるフィーチャーの数を数える
$agencyFeatureCount = [];
foreach ($filteredAgencies as $agency) {
    $count = 0;
    foreach ($features as $feature) {
        if ($feature["agency_id"] == $agency["id"] && in_array($feature["feature_id"], $_SESSION['narrowList']) && $feature["status"] == 1) {
            $count++;
        }
    }
    $agencyFeatureCount[$agency["id"]] = $count;
}

// エージェンシーの表示順序を変更する
usort($filteredAgencies, function($a, $b) use ($agencyFeatureCount) {
    $countA = $agencyFeatureCount[$a["id"]];
    $countB = $agencyFeatureCount[$b["id"]];
    // 含まれるフィーチャーの数が多い順にソートする
    return $countB - $countA;
});




// ポストリクエストを処理する
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // キープリストに追加する
    foreach ($agencies as $agency) {
        if (isset($_POST[$agency["id"]])) {
            // 同じIDが既に追加されていないか確認する
            if (!in_array($agency["id"], $_SESSION['keep_list'])) {
                // agencyテーブルのidのみをキープリストに追加する
                $_SESSION['keep_list'][] = $agency["id"];
            }
        }
    }
}
?>

<ul class="agency-infoList">
    <?php foreach ($filteredAgencies as $agency) { ?>
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
                <?php
                // エージェンシーのフィーチャーを走査して表示
                foreach ($features as $feature) {
                    if ($feature["agency_id"] == $agency["id"] && $feature["status"] == 1) { ?>
                        <li class="agency-featureItem narrow<?= $feature["feature_id"]; ?>"><?= $feature["feature_name"]; ?></li>
                <?php }
                } ?>
            </ul>

            <ul class="agency-5stepList">
                <?php foreach ($levels_info as $levelInfo) { ?>
                    <li class="agency-5stepDesc">
                        <p><?= $levelInfo["levels_name"]; ?></p>
                    </li>
                    <li>
                        <ul class="agency-5stepItem">
                            <?php foreach ($levels as $level) {
                                if ($level["agency_id"] == $agency["id"] && $level["levels_id"] == $levelInfo["id"]) { ?>
                                    <li class="agency-5stepDesc">
                                        <p><?= $level["levels_min"]; ?></p>
                                    </li>
                                    <li>
                                        <figure>
                                            <?php
                                            $levelValue = isset($level["level"]) ? $level["level"] : 0;
                                            for ($l = 0; $l < 6; $l++) {
                                                if ($levelValue == $l + 1) { ?>
                                                    <img src="../assets/img/5step-No<?= $l + 1; ?>.png" alt="" />
                                            <?php }
                                            } ?>
                                        </figure>
                                    </li>
                                    <li class="agency-5stepDesc">
                                        <p><?= $level["levels_max"]; ?></p>
                                    </li>
                            <?php }
                            } ?>
                        </ul>
                    </li>
                <?php } ?>
            </ul>

            <form action="search.php" method="POST" class="agency-keepButton">
                <!-- agencyテーブルのidのみをPOST -->
                <input type="hidden" name="<?= $agency["id"]; ?>" value="<?= $agency["id"]; ?>">
                <input class="agency-keepBtn" type="submit" name="<?= $agency["id"]; ?>" value="キープリスト登録">
            </form>

        </li>
    <?php } ?>
</ul>
