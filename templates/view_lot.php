<section class="lot-item">
        <h2>
            <?= esc($lot['name']); ?>
        </h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?= $lot['img_path']; ?>" width="730" height="548" alt="<?= esc($lot['name']); ?>">
                </div>
                <p class="lot-item__category">Категория: <span><?= $lot['category']; ?></span></p>
                <p class="lot-item__description">
                    <?= esc($lot['description']); ?>
                </p>
            </div>
            <div class="lot-item__right">
                    <div class="lot-item__state">
                        <div class="lot-item__timer timer">
                            <?= format_expiration($lot['expiration']); ?>
                        </div>
                        <div class="lot-item__cost-state">
                            <div class="lot-item__rate">
                                <span class="lot-item__amount">Текущая цена</span>
                                <span class="lot-item__cost"><?= format_price($lot['current_price']) ; ?></span>
                            </div>
                            <div class="lot-item__min-cost">
                                Мин. ставка <span><?= format_price($lot['min_bet']) ; ?> р</span>
                            </div>
                        </div>
                <? if (isset($_SESSION['user'])) { ?>        
                        <form class="lot-item__form <?= isset($error_bet)? 'form__item--invalid' : ''  ; ?>" action="bet.php" method="post">
                            <p class="lot-item__form-item">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="number" name="cost" placeholder="<?= format_price($lot['min_bet']) ; ?>">
                                <input type="hidden" name="lot_id" value="<?=$lot['id'];?>">
                                <input type="hidden" name="min_bet" value="<?=$lot['min_bet'];?>">
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    </div>
                <? } else { ?>
                    </div> 
                <? } ?>
                <? if (isset($bets)) { ?>
                    <div class="history">
                        <h3>История ставок (<span><?=count($bets); ?></span>)</h3>
                        <table class="history__list">
                            <? foreach ($bets as $bet) { ?>
                                <tr class="history__item">
                                    <td class="history__name">
                                        <?= $bet['user_name']; ?>
                                    </td>
                                    <td class="history__price">
                                        <?= format_price($bet['price']). 'р'; ?>
                                    </td>
                                    <td class="history__time">
                                        <?= format_bet_time($bet['bet_time']); ?>
                                    </td>
                                </tr>
                            <? } ?>
                        </table>
                    </div>
                <? } ?>
            </div>
        </div>
</section>