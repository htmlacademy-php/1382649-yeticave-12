<!DOCTYPE html>
<?php require_once "init.php"; ?>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Все лоты</title>
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
            <form class="main-header__search" method="get" action="" autocomplete="off">
                <input type="search" name="search" placeholder="Поиск лота">
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
                <?php foreach ($categories as $get_category) { ?>
                    <li class="nav__item">
                        <a href="all-lots.php?category=<?= htmlspecialchars($get_category) ?>"><?= htmlspecialchars($get_category) ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <div class="container">
            <section class="lots">
                <h2>Все лоты в категории <span>"<?= htmlspecialchars($_GET['category']) ?>"</span></h2>
                <ul class="lots__list">
                    <? foreach ($all_lots as $lot) { ?>

                        <li class="lots__item lot">
                            <div class="lot__image">
                                <img src="<?= htmlspecialchars($lot['image_url']) ?>" width="350" height="260"
                                     alt="<?= htmlspecialchars($lot['lot_name']) ?>">
                            </div>
                            <div class="lot__info">
                                <span class="lot__category"><?= htmlspecialchars($lot['category_name']) ?></span>
                                <h3 class="lot__title">
                                    <a class="text-link"
                                       href="lot.php?id=<?= htmlspecialchars($lot['id']) ?>"><?= htmlspecialchars($lot['name']) ?></a>
                                </h3>
                                <div class="lot__state">
                                    <div class="lot__rate">
                                        <span class="lot__amount">Стартовая цена</span>
                                        <span class="lot__cost"><?= htmlspecialchars($lot['init_price']) ?><b
                                                class="rub">р</b></span>
                                    </div>
                                    <?php
                                    remaining_time(htmlspecialchars($lot['final_date']));
                                    ?>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
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
