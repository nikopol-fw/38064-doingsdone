<?php
/**
 * Шаблон с формой регистрации
 *
 * $
 */
?>
<h2 class="content__main-heading">Регистрация аккаунта</h2>

<form class="form" action="" method="post">
  <div class="form__row">
    <label class="form__label" for="email">E-mail <sup>*</sup></label>

    <?php $classname = isset($errors['email']) ? ' form__input--error' : '';
              $value = isset($user['email']) ? htmlspecialchars($user['email']) : '';?>
    <input class="form__input<?= $classname; ?>" type="text" name="user[email]" id="email" value="<?= $value; ?>" placeholder="Введите e-mail">
    <?php if (isset($errors['email'])):
      echo '<p class="form__message">' . $errors['email'] . '</p>';
    endif; ?>
  </div>

  <div class="form__row">
    <label class="form__label" for="password">Пароль <sup>*</sup></label>

    <input class="form__input" type="password" name="user[password]" id="password" value="" placeholder="Введите пароль">
  </div>

  <div class="form__row">
    <label class="form__label" for="name">Имя <sup>*</sup></label>

    <input class="form__input" type="text" name="user[name]" id="name" value="" placeholder="Введите имя">
  </div>

  <div class="form__row form__row--controls">
    <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>

    <input class="button" type="submit" name="" value="Зарегистрироваться">
  </div>
</form>
