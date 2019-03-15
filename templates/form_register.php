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
    endif;?>
  </div>

  <div class="form__row">
    <label class="form__label" for="password">Пароль <sup>*</sup></label>

    <?php $classname = isset($errors['password']) ? ' form__input--error' : '';?>
    <input class="form__input<?= $classname; ?>" type="password" name="user[password]" id="password" value="" placeholder="Введите пароль">
    <?php if (isset($errors['password'])):
      echo '<p class="form__message">' . $errors['password'] . '</p>';
    endif;?>
  </div>

  <div class="form__row">
    <label class="form__label" for="name">Имя <sup>*</sup></label>

    <?php $classname = isset($errors['name']) ? ' form__input--error' : '';
              $value = isset($user['name']) ? htmlspecialchars($user['name']) : '';?>
    <input class="form__input<?= $classname; ?>" type="text" name="user[name]" id="name" value="<?= $value; ?>" placeholder="Введите имя">
    <?php if (isset($errors['name'])):
      echo '<p class="form__message">' . $errors['name'] . '</p>';
    endif; ?>
  </div>

  <div class="form__row form__row--controls">
    <?php if (isset($errors)):
      echo '<p class="error-message">Пожалуйста, исправьте ошибки в форме</p>';
    endif;?>

    <input class="button" type="submit" name="" value="Зарегистрироваться">
  </div>
</form>
