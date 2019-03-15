<h2 class="content__main-heading">Вход на сайт</h2>

<form class="form" action="" method="post">
  <div class="form__row">
    <label class="form__label" for="email">E-mail <sup>*</sup></label>

    <?php $classname = isset($errors['email']) ? ' form__input--error' : '';
              $value = isset($login['email']) ? htmlspecialchars($login['email']) : '';?>
    <input class="form__input<?= $classname; ?>" type="text" name="login[email]" id="email" value="<?= $value; ?>" placeholder="Введите e-mail">
    <?php if (isset($errors['email'])):
      echo '<p class="form__message">' . $errors['email'] . '</p>';
    endif;?>
  </div>

  <div class="form__row">
    <label class="form__label" for="password">Пароль <sup>*</sup></label>

    <?php $classname = isset($errors['password']) ? ' form__input--error' : '';?>
    <input class="form__input<?= $classname; ?>" type="password" name="login[password]" id="password" value="" placeholder="Введите пароль">
    <?php if (isset($errors['password'])):
      echo '<p class="form__message">' . $errors['password'] . '</p>';
    endif;?>
  </div>

  <div class="form__row form__row--controls">
    <input class="button" type="submit" name="" value="Войти">
  </div>
</form>
