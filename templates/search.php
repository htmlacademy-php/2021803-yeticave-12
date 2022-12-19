<?php

/**
 * @var array $categories
 * @var array $lots
 * @var array $time
 * @var array $value
 */

?>

<div class="page-wrapper">
    <main>
        <nav class="nav">
            <ul class="nav__list container">
                <?php foreach ($categories as $val) : ?>
                    <li class="nav__item">
                        <a href="/all-lots.php?category=<?= $val['symbol_code']; ?>"><?= htmlspecialchars($val['name']); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <div class="container">
            <section class="lots">
                <div class="lots__header">
                    <h2><?= empty($search) ? 'Пустой запрос' : 'Результаты поиска по запросу: ' ?><span><?= $search; ?></span></h2>
                </div>
                <?php if (isset($search_result)) : ?>
                    <ul class="lots__list">
                        <!--заполните этот список из массива с товарами-->
                        <?php foreach ($search_result as $value) : ?>
                            <li class="lots__item lot">
                                <div class="lot__image">
                                    <img src="<?= htmlspecialchars($value['img_url']); ?>" width="350" height="260" alt="">
                                </div>
                                <div class="lot__info">
                                    <span class="lot__category"><?= htmlspecialchars($value['cat_name']); ?></span>
                                    <h3 class="lot__title">
                                        <a class="text-link" href="/lot.php?id=<?= $value['id'] ?>"><?= htmlspecialchars($value['lot_name']); ?></a>
                                    </h3>
                                    <div class="lot__state">
                                        <div class="lot__rate">
                                            <span class="lot__amount">Стартовая цена</span>
                                            <span class="lot__cost"><?= price_format(htmlspecialchars($value['initial_price'])); ?></span>
                                        </div>
                                        <?php $time = remaining_time(htmlspecialchars($value['finished_date'])); ?>
                                        <div class="lot__timer timer <?php if ($time[0] < 1) {
                                                                            echo 'timer--finishing';
                                                                        } ?>">
                                            <?= str_pad($time[0], 2, "0", STR_PAD_LEFT) ?>:<?= str_pad($time[1], 2, "0", STR_PAD_LEFT) ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>
            <?php if (isset($pagination['countPage']) && $pagination['countPage'] > 1) : ?>
                <ul class="pagination-list">
                    <li class="pagination-item pagination-item-prev">
                        <a href="<?= '/search.php?search=' . htmlspecialchars($search) . '&page=' . $pagination['prevPage'] ?>">Назад</a>
                    </li>
                    <?php foreach ($pagination['pages'] as $value) : ?>
                        <?php if ($value === $pagination['currentPage']) : ?>
                            <li class="pagination-item pagination-item-active">
                                <a><?= $value ?></a>
                            </li>
                        <?php else : ?>
                            <li class="pagination-item">
                                <a href="<?= '/search.php?search=' . htmlspecialchars($search) . '&page=' . $value ?>"><?= $value ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <li class="pagination-item pagination-item-next">
                        <a href="<?= '/search.php?search=' . htmlspecialchars($search) . '&page=' . $pagination['nextPage'] ?>">Вперед</a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </main>
</div>