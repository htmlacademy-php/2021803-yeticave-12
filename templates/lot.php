<?php

/**
 * @var array $categories
 * @var array $lot
 * @var array $time
 */

?>
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
  <section class="lot-item container">
    <?php foreach ($lot as $value) : ?>
      <h2><?= htmlspecialchars($value['name']); ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?= htmlspecialchars($value['img_url']); ?>" width="730" height="548" alt="Сноуборд">
          </div>
          <p class="lot-item__category">Категория: <span><?= htmlspecialchars($value['cat_name']); ?></span></p>
          <p class="lot-item__description"><?= htmlspecialchars($value['description']); ?></p>
        </div>
        <div class="lot-item__right">
          <?php if (!empty($user_id)) : ?>
            <div class="lot-item__state">
              <?php $time = remaining_time(htmlspecialchars($value['finished_date'])); ?>
              <div class="lot-item__timer timer
                        <?php if ($time[0] < 1) {
                          echo 'timer--finishing';
                        } ?>">
                <?= str_pad($time[0], 2, "0", STR_PAD_LEFT) ?>:<?= str_pad($time[1], 2, "0", STR_PAD_LEFT) ?>
              </div>
              <div class="lot-item__cost-state">
                <div class="lot-item__rate">
                  <span class="lot-item__amount">Текущая цена</span>
                  <span class="lot-item__cost"><?= price_format(htmlspecialchars($value['max_price'])); ?></span>
                </div>
                <div class="lot-item__min-cost">
                  Мин. ставка <span><?= price_format(htmlspecialchars($value['max_price']) + htmlspecialchars($value['bid_step'])); ?></span>
                </div>
              </div>
              <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">
                <p class="lot-item__form-item form__item form__item--invalid">
                  <label for="cost">Ваша ставка</label>
                  <input id="cost" type="text" name="cost" placeholder="12 000">
                  <span class="form__error">Введите наименование лота</span>
                </p>
                <button type="submit" class="button">Сделать ставку</button>
              </form>
            </div>
          <?php endif; ?>
          <div class="history">
            <h3>История ставок (<span>10</span>)</h3>
            <table class="history__list">
              <tr class="history__item">
                <td class="history__name">Иван</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">5 минут назад</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Константин</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">20 минут назад</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Евгений</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">Час назад</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Игорь</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 08:21</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Енакентий</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 13:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Семён</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 12:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Илья</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 10:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Енакентий</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 13:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Семён</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 12:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Илья</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 10:20</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </section>
</main>