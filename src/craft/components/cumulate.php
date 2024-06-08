<?php
$user_info = $dbh->query('SELECT * FROM user_info')->fetchAll(PDO::FETCH_ASSOC);
$agency = $dbh->query('SELECT * FROM agency WHERE approval = 1')->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="agency-number">
    <ul class="agency-numberList">
        <li class="agency-numberItem">
            <p class="number-title">累計応募人数</p>
            <p class="number-people"><?= count($user_info);?>人</p>
        </li>
        <li class="agency-numberItem">
            <p class="number-title">累計エージェンシー数</p>
            <p class="number-agency"><?= count($agency);?>社</p>
        </li>
    </ul>
</div>