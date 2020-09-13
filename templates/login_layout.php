<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
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
            <form class="main-header__search" method="get" action="https://echo.htmlacademy.ru" autocomplete="off">
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
        <form class="form container" action="login.php" method="post"> <!-- form--invalid -->
            <h2>Вход</h2>
            <div class="form__item <?= isset($errors['email']) ? "form__item--invalid" : ""; ?>">
                <label for="email">E-mail <sup>*</sup></label>
                <input id="email" type="text" name="email" placeholder="Введите e-mail"
                       value="<?= htmlspecialchars(getPostVal($_POST['email'])) ?>">
                <span class="form__error"><?= $errors['email'] ?></span>
            </div>
            <div class="form__item <?= isset($errors['password']) ? "form__item--invalid" : ""; ?> ">
                <label for="password">Пароль <sup>*</sup></label>
                <input id="password" type="password" name="password" placeholder="Введите пароль"
                       value="<?= htmlspecialchars(getPostVal($_POST['password'])) ?>">
                <span class="form__error"><?= $errors['password'] ?></span>
            </div>
            <button type="submit" class="button">Войти</button>
        </form>
    </main>
</div>

<?php require_once 'footer.php'; ?>

</body>
</html>
