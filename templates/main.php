<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и
        горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <!--заполните этот список из массива категорий-->
        <?php

        foreach ($categories as $item) {
            ?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="pages/all-lots.html"><?= htmlspecialchars($item); ?></a>
            </li>
            <?php
        }
        ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <!--заполните этот список из массива с товарами-->
        <?php
        foreach ($announces as $announce) {
            ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= htmlspecialchars($announce['url']); ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= htmlspecialchars($announce['category']); ?></span>
                    <h3 class="lot__title"><a class="text-link"
                                              href="pages/lot.html"><?= htmlspecialchars($announce['name']); ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost">
                                <?= formatting_prices(htmlspecialchars($announce['price'])); ?>
                            </span>
                        </div>
                        <?php require_once('helpers.php');

                        $remaining_time = get_dt_range($announce['expiration_date']);
                        if ($remaining_time[0] >= '1') {
                            echo '<div class="lot__timer timer">';
                            echo $remaining_time[0] . ':' . $remaining_time[1];
                            echo '</div>';
                        } else {
                            echo '<div class="timer--finishing timer">';
                            echo $remaining_time[0] . ':' . $remaining_time[1];
                            echo '</div>';
                        }
                        ?>

                    </div>
                </div>
            </li>
            <?php
        }
        ?>
    </ul>
</section>
