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
    <form class="form container form--invalid <?= $class_name; ?>" action="sign-up.php" method="post" autocomplete="off">
        <h2>Регистрация нового аккаунта</h2>
        <?php $class_name = isset($errors['email']) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $class_name; ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= getPostVal('email'); ?>">
            <span class="form__error"><?= isset($errors['email']) ? $errors['email'] : ""; ?></span>
        </div>
        <?php $class_name = isset($errors['password']) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $class_name; ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= getPostVal('password'); ?>">
            <span class="form__error"><?= isset($errors['password']) ? $errors['password'] : ""; ?></span>
        </div>
        <?php $class_name = isset($errors['name']) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $class_name; ?>">
            <label for="name">Имя <sup>*</sup></label>
            <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= getPostVal('name'); ?>">
            <span class="form__error"><?= isset($errors['name']) ? $errors['name'] : ""; ?></span>
        </div>
        <?php $class_name = isset($errors['contacts']) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $class_name; ?>">
            <label for="message">Контактные данные <sup>*</sup></label>
            <textarea id="message" name="contacts" placeholder="Напишите как с вами связаться" value="<?= getPostVal('contacts'); ?>"></textarea>
            <span class="form__error"><?= isset($errors['contacts']) ? $errors['contacts'] : ""; ?></span>
        </div>
        <span class="form__error form__error--bottom"><?= $errors ? "Пожалуйста, исправьте ошибки в форме." : ""; ?></span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>
</main>