<?php require_once('functions.php'); ?>

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
                <input type="search" name="search" placeholder="Поиск лота"
                       value="<?= htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
                <input class="main-header__search-btn" type="submit" name="find" value="Найти">
            </form>
            <a class="main-header__add-lot button" href="add.php">Добавить лот</a>
            <nav class="user-menu">
                <?php
                if ($user_name !== null) { ?>
                    <div class="user-menu__logged">
                        <p><?= htmlspecialchars($user_name) ?></p>
                        <a class="user-menu__bets" href="my-bets.php">Мои ставки</a>
                        <a class="user-menu__logout" href="logout.php">Выход</a>
                    </div>
                <?php }
                else { ?>
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
                        <a href="all-lots.php?category=<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <div class="container">
            <section class="lots">
                <?php if (empty($search_result)) {
                    echo '<h2>' . $search_error . '</h2>';
                }
                else { ?>
                <h2>Результаты поиска по запросу «<span><?= htmlspecialchars($_GET['search']) ?></span>»</h2>
                <ul class="lots__list">
                    <?php foreach ($search_result as $lot) { ?>
                        <li class="lots__item lot">
                            <div class="lot__image">
                                <img src="<?= htmlspecialchars($lot['lot_image_url']) ?>" width="350" height="260"
                                     alt="<?= htmlspecialchars($lot['lot_name']) ?>">
                            </div>
                            <div class="lot__info">
                                <span class="lot__category"><?= htmlspecialchars($lot['lot_category']) ?></span>
                                <h3 class="lot__title"><a class="text-link"
                                                          href="lot.php?id=<?= htmlspecialchars($lot['id']) ?>"><?= htmlspecialchars($lot['lot_name']) ?></a>
                                </h3>
                                <div class="lot__state">
                                    <div class="lot__rate">
                                        <span class="lot__amount">Стартовая цена</span>
                                        <span class="lot__cost"><?= htmlspecialchars($lot['lot_init_price']) ?><b
                                                    class="rub">р</b></span>
                                    </div>
                                    <div>
                                        <?php $remaining_time = get_dt_range($lot['lot_final_date']);
                                        if ($remaining_time[0] >= '1') {
                                            echo '<div class ="lot__timer timer">';
                                            echo $remaining_time[0] . ':' . $remaining_time[1];
                                            echo '</div>';
                                        }
                                        else {
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

            <?php if ($search_result !== null) {
                if ($number_of_pages > 1) { ?>
                    <ul class="pagination-list">
                        <li class="pagination-item pagination-item-prev">
                            <?php if ($curent_page > 1 ) { ?>
                                <a href="<?= htmlspecialchars(addOrUpdateUrlParam('page',
                                    $curent_page - 1)); ?>">Назад</a>
                            <?php } ?>
                        </li>

                        <?php foreach ($pages as $page) { ?>
                            <?php $current_url = $_SERVER['REQUEST_URI']; ?>
                            <li class="pagination-item <?php if ($page === $curent_page) echo 'pagination-item-active' ?>">
                                <a href="<?= htmlspecialchars(addOrUpdateUrlParam('page', $page)) ?>"><?= $page ?></a>
                            </li>
                        <?php } ?>

                        <li class="pagination-item pagination-item-next">
                            <?php if ($curent_page < $number_of_pages) { ?>
                            <a href="<?= htmlspecialchars(addOrUpdateUrlParam('page', $curent_page + 1)) ?>">Вперед</a>
                        </li>
                    <?php } ?>
                    </ul>
                    <?php
                }
            } ?>
        </div>
    </main>
</div>

<?php require_once ('footer.php'); ?>

</body>
</html>