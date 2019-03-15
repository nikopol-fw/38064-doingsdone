<h2 class="content__main-heading">Добавление проекта</h2>

<form class="form"  action="" method="post">
  <div class="form__row">
    <label class="form__label" for="project_name">Название <sup>*</sup></label>

    <?php $classname = isset($errors['name']) ? ' form__input--error' : '';
              $value = isset($project['name']) ? htmlspecialchars($project['name']) : '';?>
    <input class="form__input<?= $classname; ?>" type="text" name="project[name]" id="project_name" value="<?= $value; ?>" placeholder="Введите название проекта">
    <?php if (isset($errors['name'])):
      echo '<p class="form__message">' . $errors['name'] . '</p>';
    endif;?>
  </div>

  <div class="form__row form__row--controls">
    <input class="button" type="submit" name="" value="Добавить">
  </div>
</form>
