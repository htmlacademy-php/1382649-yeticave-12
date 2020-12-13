<?php
require_once('init.php');
require_once('db_connection.php');
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Error! Page not found!</title>
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
                <ul class="user-menu__list">
                    <li class="user-menu__item">
                        <a href="sign-up.php">Регистрация</a>
                    </li>
                    <li class="user-menu__item">
                        <a href="login.php">Вход</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <?php

    $sql_categories = "SELECT name FROM category;";
    $categories_result = mysqli_query($db_connection, $sql_categories);
    if (!$categories_result) {
        $error = mysqli_error($db_connection);
        print("Ошибка MySQL: " . $error);
    }
    $categories = [];
    while ($category = mysqli_fetch_array($categories_result, MYSQLI_ASSOC)) {
        array_push($categories, $category['name']);
    }
    ?>

    <main>
        <nav class="nav">
            <ul class="nav__list container">
                <?php foreach ($categories as $category) { ?>
                    <li class="nav__item">
                        <a href="all-lots.php"><?= htmlspecialchars($category) ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <section class="lot-item container">
            <h2>404 Страница не найдена</h2>
            <p>Данной страницы не существует на сайте.</p>
        </section>
    </main>
</div>

<?php require_once('templates/footer.php'); ?>

</body>
</html>