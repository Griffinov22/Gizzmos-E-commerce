<!-- Testing -->
<!-- <?php var_dump($_SESSION); ?> -->

<?php

$inFolder = substr_count($_SERVER['REQUEST_URI'], '/') == 2;

$cartCount = 0;

if ($_SESSION['loggedIn']) {

    foreach($_SESSION['products'] as $headerProd) {
        $cartCount += $headerProd->amount;
    }
}

?>

<header>
    <nav class="nav">
        <ul>
            <li><a href="index.php" class="name-link">Gizzmos Inc!</a></li>
            <?php if (!$_SESSION['loggedIn']) : ?>
            <li><a href="<?= $inFolder ? '../' : '' ?>login.php" class="float-link login-link">Login</a></li>
            <li><a href="<?= $inFolder ? '../' : '' ?>sign-up.php" class="float-link signup-link">Sign Up</a></li>
            <?php else : ?>

                <?php if ($_SESSION['isAdmin']) : ?>
                <li><a href="<?= $inFolder ? '' : './admin/' ?>admin-panel.php" class="float-link admin-link">Admin</a></li>
                <?php endif; ?>

            <li><a href="<?= $inFolder ? '../' : '' ?>products.php" class="float-link prod-link">Products</a></li>
            <li><a href="<?= $inFolder ? '../' : '' ?>cart.php" class="float-link prod-link">Cart (<?= $cartCount ?>)</a></li>
            <li>
                <p><?= $_SESSION["username"]; ?></p>
            </li>
            <li><a href="../logic/logout.php" class="float-link signup-link">Logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>