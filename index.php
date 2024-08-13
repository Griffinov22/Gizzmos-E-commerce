<?php
require_once './models/product.php';
session_start();

if (!isset($_SESSION['loggedIn'])) {
    $_SESSION['loggedIn'] = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles.css">
    <title>Gizzmos Inc.</title>
</head>

<body>
    <?php include "./components/header.php"; ?>
    <main>
        <h1 class="main-header">Welcome to Gizzmos!</h1>
        <?php if (!$_SESSION["loggedIn"]) : ?>
            <p style="text-align:center; margin-top: 1rem;">Take a look at our products. Lots and lots of Gizzmos to view
            </p>
        <?php else : ?>
            <p style="text-align:center; margin-top: 1rem;">Take a look around <?= htmlspecialchars($_SESSION['firstname']) ?>! We're glad
                your here</p>
        <?php endif; ?>
    </main>


</body>

</html>