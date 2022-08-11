<?php

/**
 * @var array $products
 * @var array $categories
 * @var array $time
 * @var array $value
 */

?>

<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $val) : ?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="pages/all-lots.html"><?= $val; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($products as $value) : ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= htmlspecialchars($value['gif']); ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= htmlspecialchars($value['category']); ?> </span>
                    <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?= htmlspecialchars($value['title']); ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= price_format(htmlspecialchars($value['price'])); ?></span>
                        </div>
                        <?php $time = remaining_time(htmlspecialchars($value['date_expiration'])); ?>
                        <div class="lot__timer timer
                            <?php if ($time[0] < 1) {
                                echo 'timer--finishing';
                            } ?>">
                            <?= str_pad($time[0], 2, "0", STR_PAD_LEFT) ?>:<?= str_pad($time[1], 2, "0", STR_PAD_LEFT) ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>