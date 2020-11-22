<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($lot_name) ?></title>
    <link href="../css/normalize.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>

<body>
<div class="page-wrapper">
    <header class="main-header">
        <div class="main-header__container container">
            <h1 class="visually-hidden">YetiCave</h1>
            <a class="main-header__logo" href="index.php">
                <img src="../img/logo.svg" width="160" height="39" alt="Логотип компании YetiCave">
            </a>
            <form class="main-header__search" method="get" action="search.php" autocomplete="off">
                <input type="search" name="search" placeholder="Поиск лота">
                <input class="main-header__search-btn" type="submit" name="find" value="Найти">
            </form>
            <a class="main-header__add-lot button" href="add.php">Добавить лот</a>
            <nav class="user-menu">
                <?php
                if ($_SESSION['user']['name'] != null) { ?>
                    <div class="user-menu__logged">
                        <p><?= htmlspecialchars($user_name) ?></p>
                        <a class="user-menu__bets" href="my-bets.php">Мои ставки</a>
                        <a class="user-menu__logout" href="logout.php">Выход</a>
                    </div>
                <?php } else { ?>
                    <ul class="user-menu__list">
                        <li class="user-menu__item">
                            <a href="sign-up.php">Регистрация</a>
                        </li>
                        <li class="user-menu__item">
                            <a href="login.php">Вход</a>
                        </li>
                    </ul>
                <?php } ?>
            </nav>
        </div>
    </header>

    <main>
        <nav class="nav">
            <ul class="nav__list container">
                <?php foreach ($categories as $category) {
                    ?>
                    <li class="nav__item">
                        <a href="all-lots.php?category=<?= $category; ?>"><?= htmlspecialchars($category) ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <section class="lot-item container">
            <h2><?= htmlspecialchars($lot_name) ?></h2>
            <div class="lot-item__content">
                <div class="lot-item__left">
                    <div class="lot-item__image">
                        <img src="../<?= htmlspecialchars($image_url) ?>" width="730" height="548"
                             alt="<?= htmlspecialchars($lot_name) ?>">
                    </div>
                    <p class="lot-item__category">Категория: <span> <?= $category_id ?> </span></p>
                    <p class="lot-item__description"> <?= htmlspecialchars($description) ?></p>
                </div>
                <div class="lot-item__right">
                    <div class="lot-item__state">
                        <?php if (strtotime($expiration_date) < strtotime('now')) { ?>
                            <?= 'Срок действия лота истек' ?>
                        <?php } else { ?>
                            <?php if ($_SESSION['user']['name'] != null) { ?>

                                <div
                                    class="lot-item__timer"> <?= htmlspecialchars(remaining_time($expiration_date)) ?></div>

                                <div class="lot-item__cost-state">
                                    <div class="lot-item__rate">
                                        <span class="lot-item__amount">Текущая цена</span>
                                        <span class="lot-item__cost">
                                    <?= $last_bid_value == 0 ? formatting_prices($init_price) : formatting_prices(htmlspecialchars($last_bid_value)); ?>
                                </span>
                                    </div>

                                    <div class="lot-item__min-cost">
                                        Мин. ставка <span><?= htmlspecialchars($min_bid_value . ' р') ?></span>
                                    </div>
                                </div>
                                <?php if ($_SESSION['user']['name'] != null) { ?>
                                    <form class="lot-item__form" action="lot.php?id=<?= $_GET['id'] ?>" method="post"
                                          autocomplete="off">
                                        <p class="lot-item__form-item <?= isset($error) ? 'form__item form__item--invalid' : '' ?> ">
                                            <!--form__item form__item--invalid -->
                                            <label for="cost">Ваша ставка</label>
                                            <input id="cost" type="text" name="cost"
                                                   placeholder="<?= htmlspecialchars($min_bid_value) ?>">
                                            <span class="form__error"> <?= $error ?></span>
                                        </p>
                                        <button type="submit" class="button">Сделать ставку</button>
                                    </form>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="history">
                        <h3>История ставок (<span><?= htmlspecialchars($bids_count) ?></span>)</h3>
                        <table class="history__list">
                            <?php foreach ($bids as $bid) { ?>
                                <tr class="history__item">
                                    <td class="history__name"><?= htmlspecialchars($bid['username']); ?></td>
                                    <td class="history__price"><?= htmlspecialchars($bid['bid_value'] . ' р'); ?></td>
                                    <td class="history__time"><?= htmlspecialchars($bid['bid_data']); ?>
                                        в <?= htmlspecialchars($bid['bid_hour']) ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<?php require_once 'footer.php'; ?>

</body>
</html>
