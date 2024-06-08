<?php
$agencies = $dbh->query('SELECT * FROM agency WHERE approval = 1')->fetchAll(PDO::FETCH_ASSOC);
$features = $dbh->query("SELECT * FROM feature INNER JOIN feature_info ON feature.feature_id = feature_info.id INNER JOIN category ON feature_info.category_id = category.id")->fetchAll(PDO::FETCH_ASSOC);
$feature_info = $dbh->query("SELECT * FROM feature_info")->fetchAll(PDO::FETCH_ASSOC);
$category = $dbh->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);

foreach ($agencies as $aKey => $agency) {
    $agency["feature"] = [];
    foreach ($features as $fKey => $feature) {
        if ($feature["agency_id"] == $agency["id"]) {
            $agency["feature"][] = $feature;
        }
    }
    $agencies[$aKey] = $agency;
}


?>


<div class="narrow-set">
    <div class="narrowList">
        <div class="narrow-info">
            <p class="narrow-fig">表示件数</p>
            <!-- 表示件数改善の必要あり -->
            <p class="narrow-figure"><?= count($agencies); ?>件</p>
            <p class="narrow-searchCon">現在の検索条件</p>
            <div class="narrow-clearButton">
                <button class="narrow-clearBtn" onclick="window.location.reload();">条件クリア</button>
            </div>
        </div>
        <p class="narrow-displayDesc">以下の条件に基づいて表示しています</p>
        <ul class="narrow-List">
            <?php for ($i = 0; $i < count($category); $i++) { ?>
                <li class="narrow-Item">
                    <p class="narrow-eachTitle"><?= $category[$i]["category_name"]; ?></p>
                    <div class="narrow-eachList">
                        <?php for ($t = 0; $t < count($feature_info); $t++) {
                            if ($feature_info[$t]["category_id"] == $i + 1) { ?>
                                <input type="button" data-narrow="<?= $t + 1; ?>" class="narrow-eachBtn" value="<?= $feature_info[$t]["feature_name"]; ?> ">
                        <?php }
                        } ?>
                    </div>
                </li>
            <?php } ?>
        </ul>
        <div class="narrow-search">
            <!-- <form id="myForm" action="search.php" method="POST"> -->
            <form id="myForm" action="search.php" method="POST">
                <input type="hidden" name="narrowList" id="narrowListInput" value="">
                <button type="button" id="narrow-search" class="narrow-searchBtn">検索</button>
            </form>
        </div>
    </div>
</div>


<?php

// echo '<pre>';
// var_dump($agencies);
// echo '</pre>';


// echo '<pre>';
// var_dump($feature_info);
// echo '</pre>';

// echo '<pre>';
// var_dump($category);
// echo '</pre>';
?>