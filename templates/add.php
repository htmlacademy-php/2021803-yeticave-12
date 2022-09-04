<?php

/**
 * @var array $categories
 * @var array $errors
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
    <?php $class_name = !empty($errors) ? "form--invalid" : "" ?>
    <form class="form form--add-lot container <?= $class_name; ?>" action="add.php" method="post" enctype="multipart/form-data">
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <?php $class_name = isset($errors['name']) ? "form__item--invalid" : "" ?>
            <div class="form__item <?= $class_name; ?>">
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="name" placeholder="Введите наименование лота" value=<?= getPostVal('name'); ?>>
                <span class="form__error"><?= isset($errors['name']) ? $errors['name'] : "" ?></span>
            </div>
            <?php $class_name = isset($errors['category_id']) ? "form__item--invalid" : "" ?>
            <div class="form__item <?= $class_name; ?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="category_id">
                    <option>Выберите категорию</option>
                    <?php foreach ($categories as $val) : ?>
                        <option value="<?= $val['id']; ?>" <?php if ($val['id'] == getPostVal('category_id')) : ?> selected<?php endif; ?>><?= htmlspecialchars($val['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form__error"><?= isset($errors['category_id']) ? $errors['category_id'] : "" ?></span>
            </div>
        </div>
        <?php $class_name = isset($errors['description']) ? "form__item--invalid" : "" ?>
        <div class="form__item form__item--wide <?= $class_name; ?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="description" placeholder="Напишите описание лота"><?= getPostVal('description'); ?></textarea>
            <span class="form__error"><?= isset($errors['description']) ? $errors['description'] : "" ?></span>
        </div>
        <?php $class_name = isset($errors['img_url']) ? "form__item--invalid" : "" ?>
        <div class="form__item form__item--file <?= $class_name; ?>">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="lot-img" name="img_url" value="">
                <label for="lot-img">
                    Добавить
                </label>
            </div>
            <span class="form__error"><?= isset($errors['img_url']) ? $errors['img_url'] : "" ?></span>
        </div>
        <?php $class_name = isset($errors['initial_price']) ? "form__item--invalid" : "" ?>
        <div class="form__container-three">
            <div class="form__item form__item--small <?= $class_name; ?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="initial_price" placeholder="0" value=<?= getPostVal('initial_price'); ?>>
                <span class="form__error"><?= isset($errors['initial_price']) ? $errors['initial_price'] : "" ?></span>
            </div>
            <?php $class_name = isset($errors['bid_step']) ? "form__item--invalid" : "" ?>
            <div class="form__item form__item--small <?= $class_name; ?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="bid_step" placeholder="0" value=<?= getPostVal('bid_step'); ?>>
                <span class="form__error"><?= isset($errors['bid_step']) ? $errors['bid_step'] : "" ?></span>
            </div>
            <?php $class_name = isset($errors['finished_date']) ? "form__item--invalid" : "" ?>
            <div class="form__item <?= $class_name; ?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="finished_date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= getPostVal('finished_date'); ?>">
                <span class="form__error"><?= isset($errors['finished_date']) ? $errors['finished_date'] : '' ?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
    <script src="../flatpickr.js"></script>
    <script src="../script.js"></script>
</main>