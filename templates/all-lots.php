<?php

/**
 * @var array $categories
 * @var array $lot
 * @var array $time
 * @var string $lots_category
 */
?>
<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $val) : ?>
                <li class="nav__item <?php if ($lots_category == $val['symbol_code']) echo 'nav__item--current'; ?>">
                    <a href="/all-lots.php?category=<?= $val['symbol_code']; ?>"><?= htmlspecialchars($val['name']); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <section class="lots container">
        <div class="lots__header">
            <?php foreach ($categories as $val) : ?>
                <?php if ($lots_category === $val['symbol_code']) : ?>
                    <h2>Все лоты в категории <?= '«' . $val['name'] . '»'; ?></h2>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <ul class="lots__list">
            <?php foreach ($lot as $value) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= htmlspecialchars($value['img_url']); ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= htmlspecialchars($value['name_category']); ?> </span>
                        <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?= htmlspecialchars($value['id']); ?>"><?= htmlspecialchars($value['name']); ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= price_format(htmlspecialchars($value['initial_price'])); ?></span>
                            </div>
                            <?php $time = remaining_time(htmlspecialchars($value['finished_date']), date('Y-m-d H:i:s')); ?>
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
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
        <li class="pagination-item pagination-item-active"><a>1</a></li>
        <li class="pagination-item"><a href="#">2</a></li>
        <li class="pagination-item"><a href="#">3</a></li>
        <li class="pagination-item"><a href="#">4</a></li>
        <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
    </ul>
</main>