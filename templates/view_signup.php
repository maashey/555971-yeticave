<form class="form <?= (isset($errors) && count($errors)>0 )? 'form--invalid' : '' ; ?>" action="signup.php" method="post" enctype="multipart/form-data" >
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?= isset($errors['email'])? 'form__item--invalid' : ''  ; ?>">
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= isset($form['email']) ? trim($form['email']) : '' ; ?>">
        <span class="form__error">
            <?= $errors['email'] ?? '' ;?>
        </span>
    </div>
    <div class="form__item <?= isset($errors['password'])? 'form__item--invalid' : ''  ; ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль" value="<?= $form['password'] ?? '' ; ?>">
        <span class="form__error">
            <?= $errors['password'] ?? '' ;?>
        </span>
    </div>
    <div class="form__item  <?= isset($errors['name'])? 'form__item--invalid' : ''  ; ?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="name" placeholder="Введите имя"  value="<?= $form['name'] ?? '' ; ?>">
        <span class="form__error">
            <?= $errors['name'] ?? '' ;?>
        </span>
    </div>
    <div class="form__item <?= isset($errors['message'])? 'form__item--invalid' : ''  ; ?>">
        <label for="message">Контактные данные*</label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться" ><?= $form['message'] ?? '' ; ?></textarea>
        <span class="form__error">
            <?= $errors['message'] ?? '' ;?>
        </span>
    </div>
    <div class="form__item form__item--file form__item--last <?= isset($errors['avatar'])? 'form__item--invalid' : ''  ?>">
        <label>Аватар</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="photo2" value="" name="avatar">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
        <span class="form__error">
            <?= $errors['avatar'] ?? '' ;?>
        </span>
        <span style="color: #25434e; font-size: 11px; " >
            <?= $file_message ?? '' ;?>
        </span>
    </div>
    <span class="form__error form__error--bottom">
        <?=isset($errors)? 'Пожалуйста, исправьте ошибки в форме.' : '' ;?>
    </span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>