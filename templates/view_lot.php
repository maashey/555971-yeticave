<section class="lot-item">
    <?php if (isset($lot)): ?>
        <h2>
            <?= esc($lot['name']); ?>
        </h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?= $lot['img']; ?>" width="730" height="548" alt="<?= esc($lot['name']); ?>">
                </div>
                <p class="lot-item__category">Категория: <span><?= $lot['category']; ?></span></p>
                <p class="lot-item__description">
                    <?= esc($lot['description']); ?>
                </p>
            </div>
            <div class="lot-item__right">
                <? if (isset($_SESSION['user'])) { ?>
                    <div class="lot-item__state">
                        <div class="lot-item__timer timer">
                            <?= $expiration; ?>
                        </div>
                        <div class="lot-item__cost-state">
                            <div class="lot-item__rate">
                                <span class="lot-item__amount">Текущая цена</span>
                                <span class="lot-item__cost"><?= format_price($lot['price']) ; ?></span>
                            </div>
                            <div class="lot-item__min-cost">
                                Мин. ставка <span><?= format_price($lot['price']) ; ?> р</span>
                            </div>
                        </div>
                        <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post">
                            <p class="lot-item__form-item">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="number" name="cost" placeholder="12 000">
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    </div>
                <? } ?>
                <? if (isset($bets)) { ?>
                    <div class="history">
                        <h3>История ставок (<span><?=count($bets); ?></span>)</h3>
                        <table class="history__list">
                            <? foreach ($bets as $bet) { ?>
                                <tr class="history__item">
                                    <td class="history__name">
                                        <?= $bet['name']; ?>
                                    </td>
                                    <td class="history__price">
                                        <?= format_price($bet['price']). 'р'; ?>
                                    </td>
                                    <td class="history__time">
                                        <?= date("d.m.y \в H:i",$bet['ts'] ); ?>
                                    </td>
                                </tr>
                            <? } ?>
                        </table>
                    </div>
                <? } ?>
            </div>
        </div>
    <?php else: ?>
        <h1 style="color: black">Лот с этим ID не найден</h1>
    <?php endif; ?>
</section>