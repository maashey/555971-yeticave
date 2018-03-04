<div class="container">
    <section class="lots">
        <h2>История просмотров</h2>
        <ul class="lots__list">
            <? foreach($lots as $lot) { ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?=esc($lot['img_path']); ?>" width="350" height="260"
                             alt="<?=esc($lot['name']); ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=esc($lot['category']); ?></span>
                        <h3 class="lot__title"><a class="text-link" href="lot.php?lot_id=<?=$lot['id']; ?>"><?=esc($lot['name']); ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?=format_price($lot['price']); ?><b class="rub">р</b></span>
                            </div>
                            <div class="lot__timer timer">
                                <?= $lot['expiration']; ?>
                            </div>
                        </div>
                    </div>
                </li>
            <? } ?>
        </ul>
    </section>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
        <li class="pagination-item pagination-item-active"><a>1</a></li>
        <li class="pagination-item"><a href="#">2</a></li>
        <li class="pagination-item"><a href="#">3</a></li>
        <li class="pagination-item"><a href="#">4</a></li>
        <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
    </ul>
</div>