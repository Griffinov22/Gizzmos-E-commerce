<?php
session_start();
if (!$_SESSION['loggedIn']) {
    header("Location: index.php");
    exit();
}

    include "./conn/openDb.php";
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
    <h1 class="main-header">All Gizzmos, Gadgets, and Thingimabobs</h1>
    <p class="text-center">From a small, local company 👍</p>

    <ul class="gad__grid">

        <?php
        $products = $db->query("SELECT * FROM Products")->fetchAll();

        foreach ($products as $prod):
            
        ?>
            <li class="gad__item">
                <a href="description.php">
                    <img class="gad__img" src="./images/default-image.jpg" alt="" />
                    <div class="gad__content">
                        <h3 class="gad__title"><?= $prod['Name'] ?></h3>
                        <div class="gad__flex">
                            <p class="gad__desc"><?= $prod['Description'] ?>
                        </p>
                            <p class="gad__price"><span>$</span><?= $prod['Price'] ?></p>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>

    </ul>
</body>

</html>