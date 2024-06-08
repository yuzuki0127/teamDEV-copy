<?php
$agencies = $dbh->query('SELECT * FROM agency ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>


<section class="splide hero-section">
    <div class="splide__track">
        <ul class="hero-list splide__list">
            <li class="hero-item splide__slide">
                <figure class="hero-pc hero-No1">
                    <img src="../assets/img/hero-No1.png" alt="">
                    <button class="hero-infoBtn">詳細</button>
                </figure>
                <figure class="hero-sp hero-No1">
                    <img src="../assets/img/hero-spNo1.png" alt="">
                    <button class="hero-infoBtn">詳細</button>
                </figure>
                <div class="splide-popup splide-hidden">
                <p class="popup-item">就職エージェント（就活エージェント） 4つのサービス<br>
1.個別カウンセリングの実施<br>
就職エージェントでは一人ひとりに面談を実施しており、個々の状況や希望をヒアリングしたうえで就活の相談に乗ってくれます。個別面談なので、プライベートな事情も相談しやすいのがメリットです。<br><br>
2.求人先の紹介<br>
就職エージェントでは、ヒアリングした求職者の希望条件や経歴を踏まえて、担当のアドバイザーが求人を紹介してくれます。民間の人材派遣会社が就職エージェントを運営していることも多く、企業とのコネクションがあるのも強みです。
求職サイトでは公開されていない求人を紹介してもらえたり、企業の実情を教えてもらえたりする可能性もあります。自分一人で就活するよりも、希望に合った求人と出会う確率を高められるでしょう。<br><br>
3.書類作成のサポート<br>
就職エージェントでは、履歴書やESの添削を行っています。アドバイザーは応募先企業がどのような人材を求めているかを熟知しているため、的確なアドバイスが可能です。就職エージェントを通して履歴書・ESを添削してもらうことで、書類選考の通過率アップが期待できます。<br><br>
4.面接対策の実施<br>
就職エージェントでは、面接に関するサポートも受けられます。専任のアドバイザーが面接マナーやよく聞かれる質問などを教えてくれるうえ、応募先企業の基準に合わせた練習も可能です。
また、本番を意識した面接練習に対応してくれる就職エージェントもあるので、しっかりと準備をしたうえで面接に臨めるでしょう。</p>
                    <button class="splide-closeButton"></button>
                </div>
            </li>
            <li class="hero-item splide__slide">
                <figure class="hero-pc">
                    <img src="../assets/img/hero-No2.png" alt="">
                </figure>
                <figure class="hero-sp">
                    <img src="../assets/img/hero-spNo2.png" alt="">
                </figure>
            </li>
            <li class="hero-item splide__slide">
                <figure class="hero-No3">
                    <img src="../assets/img/hero-No3.png" alt="">
                    <ul class="hero-newList">
                        <?php for ($i = 0; $i < 3; $i++) { ?>
                            <li class="hero-newItem">
                                <button class="hero-Item<?= $agencies[$i]["id"]; ?>"><?= $i + 1; ?>. <?= $agencies[$i]["agency_name"]; ?></button>
                            </li>
                        <?php } ?>
                    </ul>
                </figure>
            </li>
        </ul>
        <div class="splide__arrows">
            <button class="splide__arrow splide__arrow--prev button prev"></button>
            <button class="splide__arrow splide__arrow--next button next"></button>
        </div>
    </div>
</section>