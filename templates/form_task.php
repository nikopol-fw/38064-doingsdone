<?php
/**
 * Шаблон с формой для добавления задачи
 *
 * $projects - массив с данными о проектах
 * $projects['project_id'] - id проекта
 * $projects['project_name'] - имя проекта
 */
?>
<h2 class="content__main-heading">Добавление задачи</h2>

<form class="form" action="" method="post" enctype="multipart/form-data">
  <div class="form__row">
    <label class="form__label" for="name">Название <sup>*</sup></label>

    <?php $classname = isset($errors['name']) ? ' form__input--error' : '';
              $value = isset($task['name']) ? htmlspecialchars($task['name']) : ''; ?>
    <input class="form__input<?= $classname; ?>" type="text" name="task[name]" id="name" value="<?= $value; ?>" placeholder="Введите название">
    <?php if (isset($errors['name'])):
      echo '<p class="form__message">' . $errors['name'] . '</p>';
    endif; ?>
  </div>


  <div class="form__row">
    <label class="form__label" for="project">Проект</label>

    <?php $classname = isset($errors['project']) ? ' form__input--error' : '';
              $value = isset($task['project']) ? htmlspecialchars($task['project']) : ''; ?>
    <select class="form__input form__input--select<?= $classname; ?>" name="task[project]" id="project">
      <?php foreach ($projects as $index => $project): ?>
        <option value="<?= $project['project_id']; ?>" <?php
        if ($value === $project['project_id']):
          echo 'selected';
        endif;
        ?>><?= $project['project_name']; ?></option>
      <?php endforeach; ?>
    </select>
    <?php if (isset($errors['project'])):
      echo '<p class="form__message">' . $errors['project'] . '</p>';
    endif; ?>
  </div>


  <div class="form__row">
    <label class="form__label" for="date">Дата выполнения</label>

    <?php $classname = isset($errors['date']) ? ' form__input--error' : '';
              $value = isset($task['date']) ? htmlspecialchars($task['date']) : ''; ?>
    <input class="form__input form__input--date<?= $classname; ?>" type="date" name="task[date]" id="date" value="<?= $value; ?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
    <?php if (isset($errors['date'])):
      echo '<p class="form__message">' . $errors['date'] . '</p>';
    endif; ?>
  </div>


  <div class="form__row">
    <label class="form__label" for="preview">Файл</label>

    <div class="form__input-file">
      <input class="visually-hidden" type="file" name="task[preview]" id="preview" value="">

      <label class="button button--transparent" for="preview">
        <span>Выберите файл</span>
      </label>
    </div>
  </div>

  <div class="form__row form__row--controls">
    <input class="button" type="submit" name="" value="Добавить">
  </div>
</form>
