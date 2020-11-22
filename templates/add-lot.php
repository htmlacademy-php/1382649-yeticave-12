<?php require_once('helpers.php'); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link href="../css/normalize.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/flatpickr.min.css" rel="stylesheet">
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
                <div class="user-menu__logged">
                    <p><?= htmlspecialchars($username) ?></p>
                    <a class="user-menu__bets" href="my-bets.php">Мои ставки</a>
                    <a class="user-menu__logout" href="logout.php">Выход</a>
                </div>
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
                <?php }
                ?>
            </ul>
        </nav>

        <form class="form form--add-lot container form--invalid" name="add-lot" action="add.php" method="post"
              enctype="multipart/form-data">
            <h2>Добавление лота</h2>
            <div class="form__container-two">
                <div class="form__item  <?= isset($errors['lot-name']) ? "form__item--invalid" : ""; ?>">
                    <label for="lot-name">Наименование <sup>*</sup></label>
                    <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота"
                           value="<?= getPostVal(htmlspecialchars('lot-name')); ?>">
                    <span class="form__error"><?= $errors['lot-name'] ?></span>
                </div>
                <div class="form__item <?= isset($errors['category']) ? "form__item--invalid" : ""; ?>">
                    <!-- form__item--invalid -->
                    <label for="category">Категория <sup>*</sup></label>
                    <select id="category" name="category"
                            value="<?= getPostVal('category'); ?>">
                        <option>Выберите категорию</option>
                        <?php foreach ($categories as $category) { ?>
                            <option><?= htmlspecialchars($category) ?></option>
                        <?php } ?>
                    </select>
                    <span class="form__error"><?= $errors['category'] ?></span>
                </div>
            </div>
            <div class="form__item form__item--wide <?= isset($errors['message']) ? "form__item--invalid" : ""; ?>">
                <label for="message">Описание <sup>*</sup></label>
                <textarea id="message" type="text" name="message"
                          placeholder="Напишите описание лота"><?= htmlspecialchars(getPostVal('message')); ?></textarea>
                <span class="form__error"><?= $errors['message'] ?></span>
            </div>
            <div class="form__item form__item--file <?= isset($errors['lot-image']) ? "form__item--invalid" : ""; ?>">
                <label>Изображение <sup>*</sup></label>
                <div class="form__input-file">
                    <input class="visually-hidden" type="file" name="lot-image" id="lot-img">
                    <label for="lot-img">
                        Добавить
                    </label>
                </div>
                <span class="form__error"><?= $errors['lot-image'] ?></span>
            </div>
            <div class="form__container-three">
                <div
                    class="form__item form__item--small <?= isset($errors['lot-rate']) ? "form__item--invalid" : ""; ?>">
                    <label for="lot-rate">Начальная цена <sup>*</sup></label>
                    <input id="lot-rate" type="int" name="lot-rate" placeholder="0"
                           value="<?= htmlspecialchars(getPostVal('lot-rate')); ?>">
                    <span class="form__error"><?= $errors['lot-rate'] ?></span>
                </div>
                <div
                    class="form__item form__item--small <?= isset($errors['lot-step']) ? "form__item--invalid" : ""; ?>">
                    <label for="lot-step">Шаг ставки <sup>*</sup></label>
                    <input id="lot-step" type="int" name="lot-step" placeholder="0"
                           value="<?= htmlspecialchars(getPostVal('lot-step')); ?>">
                    <span class="form__error"><?= $errors['lot-step'] ?></span>
                </div>
                <div class="form__item <?= isset($errors['lot-date']) ? "form__item--invalid" : ""; ?>">
                    <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                    <input class="form__input-date" id="lot-date" type="text" name="lot-date"
                           placeholder="Введите дату в формате ГГГГ-ММ-ДД"
                           value="<?= htmlspecialchars(getPostVal('lot-date')); ?>">
                    <span class="form__error"><?= $errors['lot-date'] ?></span>
                </div>
            </div>
            <span class="form__error form__error--bottom"><?= $warning_about_errors ?></span>
            <button type="submit" class="button" name="submit_btn">Добавить лот</button>
        </form>
    </main>
</div>

<?php require_once 'footer.php'; ?>

<script src="../flatpickr.js"></script>
<script src="../script.js"></script>
</body>
</html>
