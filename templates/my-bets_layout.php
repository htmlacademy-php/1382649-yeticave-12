<?php require_once('helpers.php');
require_once('db_connection.php');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои ставки</title>
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
                <div class="user-menu__logged">
                    <p><?= htmlspecialchars($username) ?></p>
                    <a class="user-menu__bets user-menu" href="my-bets.php">Мои ставки</a>
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
                        <a href="all-lots.php?category=<?= htmlspecialchars($category); ?>"><?= htmlspecialchars($category); ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <section class="rates container">
            <h2>Мои ставки</h2>
            <table class="rates__list">
                <?php foreach ($user_bets as $user_bet) {
                    $time_val = remaining_time_bet($user_bet['fin_data'], $user_bet['user_id'], $user_bet['lot_id'], $db_connection) ?>
                    <tr class=" <?= htmlspecialchars($time_val['line_background_style']) ?>">
                        <td class="rates__info">
                            <div class="rates__img">
                                <img src="<?= htmlspecialchars($user_bet['img_url']) ?>" width="54" height="40"
                                     alt="<?= htmlspecialchars($user_bet['lot_name']) ?>>">
                            </div>
                            <div>
                                <h3 class="rates__title">
                                    <a href="lot.php?id=<?= htmlspecialchars($user_bet['lot_id']); ?>"><?= htmlspecialchars($user_bet['lot_name']); ?></a>
                                </h3>
                                <p><?= $time_val['addres']; ?></p>
                            </div>
                        </td>
                        <td class="rates__category">
                            <?= htmlspecialchars($user_bet['category']) ?>
                        </td>
                        <td class="rates__timer">
                            <div
                                class="<?= htmlspecialchars($time_val['column_background_style']) ?>">
                                <?= htmlspecialchars($time_val['text']); ?></div>
                        </td>
                        <td class="rates__price">
                            <?= htmlspecialchars($user_bet['price']); ?>
                        </td>
                        <td class="rates__time">
                            <?= htmlspecialchars(bid_time($user_bet['bid_time'])); ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </section>
    </main>

</div>

<?php require_once('footer.php'); ?>

</body>
</html>
