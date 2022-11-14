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
  <form class="form container <?= $class_name; ?>" action="login.php" method="post">
    <h2>Вход</h2>
    <?php $class_name = isset($errors['email']) ? "form__item--invalid" : ""; ?>
    <div class="form__item <?= $class_name; ?>">
      <label for="email">E-mail <sup>*</sup></label>
      <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= getPostVal('email'); ?>">
      <span class="form__error"><?= isset($errors['email']) ? $errors['email'] : ""; ?></span>
    </div>
    <?php $class_name = isset($errors['password']) ? "form__item--invalid" : ""; ?>
    <div class="form__item form__item--last <?= $class_name; ?>">
      <label for="password">Пароль <sup>*</sup></label>
      <input id="password" type="password" name="password" placeholder="Введите пароль">
      <span class="form__error"><?= isset($errors['password']) ? $errors['password'] : ""; ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
  </form>
</main>