
<section class="lots container">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <? foreach($lots as $lot) { ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= $lot['img']; ?>" width="350" height="260" alt="<?= esc($lot['name']); ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= $lot['category']; ?></span>
                        <h3 class="lot__title"><a class="text-link" href="<?= 'lot.php?id='.$lot['id']; ?>"><?= esc($lot['name']); ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= format_price($lot['price']) ; ?><b class="rub">р</b></span>
                            </div>
                            <div class="lot__timer timer">
                                <?= format_expiration($lot['expiration']) ; ?>
                            </div>
                        </div>
                    </div>
                </li>
        <? } ?>    
    </ul>
</section>