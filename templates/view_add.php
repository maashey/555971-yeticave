<?php if (isset($_SESSION['user'])) : ?>
    <form class="form form--add-lot <?= (isset($errors) && count($errors)>0 )? 'form--invalid' : '' ; ?>" action="add.php" method="post" enctype="multipart/form-data">
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item <?= isset($errors['name'])? 'form__item--invalid' : ''  ; ?>">
                <label for="lot-name">Наименование</label>
                <input id="lot-name" type="text" name="name" placeholder="Введите наименование лота" value="<?= $lot['name'] ?? '' ; ?>">
                <span class="form__error">
                    <?= isset($errors['name'])? $dict['name'].': '.$errors['name'] : '' ;?>
                </span>
            </div>

            <div class="form__item <?= isset($errors['category'])? 'form__item--invalid' : '' ; ?>">
                <label for="category">Категория</label>
                <select id="category" name="category">
                    <option>Выберите категорию</option>
                    <? foreach ($categories as $cat) {
                        if ( isset($lot['category']) && ($cat==$lot['category']) ) { ?>
                            <option <?= 'selected >'. $cat ;?></option>
                        <? }else{ ?>
                            <option><?= $cat ;?></option>
                        <? } ?>
                    <? } ?>
                </select>
                <span class="form__error">
                    <?= isset($errors['category'])? $dict['category'].': '.$errors['category'] : '' ;?>
                </span>
            </div>
        </div>

        <div class="form__item form__item--wide <?= isset($errors['description'])? 'form__item--invalid' : ''  ?>">
            <label for="message">Описание</label>
            <textarea id="message" name="description" placeholder="Напишите описание лота"><?= $lot['description'] ?? '' ; ?></textarea>
            <span class="form__error">
                <?= isset($errors['description'])? $dict['description'].': '.$errors['description'] : '' ;?>
            </span>
        </div>

        <div class="form__item form__item--file  <?= isset($errors['img'])? 'form__item--invalid' : ''  ?>">
            <label>Изображение</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
                </div>
            </div>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="photo2" value="" name="img">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
            <span class="form__error">
                <?= isset($errors['img'])? $dict['img'].': '.$errors['img'] : '' ;?>
            </span>
        </div>

        <div class="form__container-three">
            <div class="form__item form__item--small <?= isset($errors['price'])? 'form__item--invalid' : ''  ; ?>">
                <label for="lot-rate">Начальная цена</label>
                <input id="lot-rate" type="" name="price" placeholder="0" value="<?= $lot['price'] ?? ''; ?>">
                <span class="form__error">
                    <?= isset($errors['price'])? $dict['price'].': '.$errors['price'] : '' ;?>
                </span>
            </div>

            <div class="form__item form__item--small <?= isset($errors['step'])? 'form__item--invalid' : ''  ; ?>">
                <label for="lot-step">Шаг ставки</label>
                <input id="lot-step" type="" name="step" placeholder="0" value="<?= $lot['step'] ?? '' ; ?>">
                <span class="form__error">
                    <?= isset($errors['step'])? $dict['step'].': '.$errors['step'] : '' ;?>
                </span>
            </div>

            <div class="form__item <?= isset($errors['expiration'])? 'form__item--invalid' : ''  ; ?>">
                <label for="lot-date">Дата окончания торгов</label>
                <input class="form__input-date" id="lot-date" type="date" name="expiration" value="<?= $lot['expiration'] ?? ''; ?>">
                <span class="form__error">
                    <?= isset($errors['expiration'])? $dict['expiration'].': '.$errors['expiration'] : '' ;?>
                </span>
            </div>
        </div>

        <span class="form__error form__error--bottom">
            <?=isset($errors)? 'Пожалуйста, исправьте ошибки в форме.' : '' ;?>
        </span>
        <button type="submit" class="button" name="send">Добавить лот</button>
    </form>
<?php else: ?>
    <div class="container">
        <h1 style="color: black">Залогиньтесь для добавления лота</h1>
    </div>
<?php endif; ?>