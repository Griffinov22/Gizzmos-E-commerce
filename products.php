<?php
session_start();
if (!$_SESSION['loggedIn']) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Gizzmos Inc - Products</title>
</head>

<body>
    <?php include "./components/header.php" ?>
    <h1 class="main-header">All Gizzmos, Gadgets, and Thingimabobs sold from a small company
    </h1>
</body>

</html>