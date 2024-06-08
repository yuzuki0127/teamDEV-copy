<?php
$agencies = $dbh->query('SELECT * FROM agency ORDER BY updated_at DESC')->fetchAll(PDO::FETCH_ASSOC);

?>

<section class="edited-agency">
    <p class="edited-possibleTitle">最近編集されたエージェンシー</p>
    <ul class="edited-agencyList">
        <?php for ($i = 0; $i < 3; $i++) { ?>
            <li class="edited-agencyItem">
                <button class="hero-Item<?= $agencies[$i]["id"]; ?>"><?= $i + 1; ?>. <?= $agencies[$i]["agency_name"]; ?></button>
            </li>
        <?php } ?>
    </ul>
</section>