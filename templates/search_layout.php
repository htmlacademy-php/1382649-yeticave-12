<?php require_once('helpers.php'); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результаты поиска</title>
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
                <input type="search" name="search" placeholder="Поиск лота" value="<?= $_GET['search'] ?>">
                <input class="main-header__search-btn" type="submit" name="find" value="Найти">
            </form>
            <a class="main-header__add-lot button" href="add.php">Добавить лот</a>
            <nav class="user-menu">
                <?php
                if ($_SESSION['user']['name'] != null) { ?>
                    <div class="user-menu__logged">
                        <p><?= $user_name ?></p>
                        <a class="user-menu__bets" href="pages/my-bets.html">Мои ставки</a>
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
                <?php foreach ($categories as $category) { ?>
                    <li class="nav__item">
                        <a href="all-lots.php?category=<?= $category ?>"><?= $category ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <div class="container">
            <section class="lots">
                <?php if (empty($search_result)) {
                    echo '<h2>' . $search_error . '</h2>';
                } else { ?>
                <h2>Результаты поиска по запросу «<span><?= $_GET['search'] ?></span>»</h2>
                <ul class="lots__list">
                    <?php foreach ($search_result as $lot) { ?>
                        <li class="lots__item lot">
                            <div class="lot__image">
                                <img src="/<?= $lot['lot_image_url'] ?>" width="350" height="260"
                                     alt="<?= $lot['lot_name'] ?>">
                            </div>
                            <div class="lot__info">
                                <span class="lot__category"><?= $lot['lot_category'] ?></span>
                                <h3 class="lot__title"><a class="text-link"
                                                          href="lot.php?id=<?= $lot['id'] ?>"><?= $lot['lot_name'] ?></a>
                                </h3>
                                <div class="lot__state">
                                    <div class="lot__rate">
                                        <span class="lot__amount">Стартовая цена</span>
                                        <span class="lot__cost"><?= $lot['lot_init_price'] ?><b class="rub">р</b></span>
                                    </div>
                                    <div>
                                        <?php $remaining_time = get_dt_range($lot['lot_final_date']);
                                        if ($remaining_time[0] >= '1') {
                                            echo '<div class ="lot__timer timer">';
                                            echo $remaining_time[0] . ':' . $remaining_time[1];
                                            echo '</div>';
                                        } else {
                                            echo '<div class="timer--finishing timer">';
                                            echo $remaining_time[0] . ':' . $remaining_time[1];
                                            echo '</div>';
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php }
                    } ?>
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
    </main>

</div>

<?php require_once 'footer.php'; ?>

</body>
</html>
