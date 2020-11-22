<?php require_once('helpers.php'); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
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
            <form class="main-header__search" method="get" action="sign-up.php" autocomplete="off">
                <input type="search" name="search" placeholder="Поиск лота">
                <input class="main-header__search-btn" type="submit" name="find" value="Найти">
            </form>
            <a class="main-header__add-lot button" href="../pages/add-lot.html">Добавить лот</a>
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
        <form class="form container form--invalid" action="sign-up.php" method="post"
              autocomplete="off">
            <h2>Регистрация нового аккаунта</h2>
            <div class="form__item <?= isset($errors['email']) ? "form__item--invalid" : ""; ?>">
                <label for="email">E-mail <sup>*</sup></label>
                <input id="email" type="text" name="email" placeholder="Введите e-mail"
                       value="<?= getPostVal(htmlspecialchars('email')) ?>">
                <span class="form__error"><?= $errors['email'] ?></span>
            </div>
            <div class="form__item <?= isset($errors['password']) ? "form__item--invalid" : ""; ?>">
                <label for="password">Пароль <sup>*</sup></label>
                <input id="password" type="password" name="password" placeholder="Введите пароль">
                <span class="form__error"><?= $errors['password'] ?></span>
            </div>
            <div class="form__item <?= isset($errors['name']) ? "form__item--invalid" : ""; ?>">
                <label for="name">Имя <sup>*</sup></label>
                <input id="name" type="text" name="name" placeholder="Введите имя"
                       value="<?= getPostVal(htmlspecialchars('name')) ?>">
                <span class="form__error"><?= $errors['name'] ?></span>
            </div>
            <div class="form__item <?= isset($errors['message']) ? "form__item--invalid" : ""; ?>">
                <label for="message">Контактные данные <sup>*</sup></label>
                <textarea id="message" name="message" placeholder="Напишите как с вами связаться"
                          ?> <?= getPostVal(htmlspecialchars('message')) ?></textarea>
                <span class="form__error"><?= $errors['message'] ?></span>
            </div>
            <span class="form__error form__error--bottom"><?= $warning_about_errors ?></span>
            <button type="submit" class="button">Зарегистрироваться</button>
            <a class="text-link" href="login.php">Уже есть аккаунт</a>
        </form>
    </main>
</div>

<?php require_once 'footer.php'; ?>

</body>
</html>
