<?php var_dump($_SESSION); ?>
<header>
    <nav class="nav">
        <ul>
            <li><a href="index.php" class="name-link">Gizzmos Inc!</a></li>
            <?php if (!$_SESSION['loggedIn']) : ?>
            <li><a href="login.php" class="float-link login-link">Login</a></li>
            <li><a href="sign-up.php" class="float-link signup-link">Sign Up</a></li>
            <?php else : ?>

            <?php if ($_SESSION['isAdmin']) : ?>
            <li><a href="../admin/admin-panel.php" class="float-link admin-link">Admin</a></li>
            <?php endif; ?>

            <li><a href="products.php" class="float-link prod-link">Products</a></li>
            <li>
                <p><?= $_SESSION["username"]; ?></p>
            </li>
            <li><a href="../logic/logout.php" class="float-link signup-link">Logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>