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
    <h1 class="main-header">All Gizzmos, Gadgets, and Thingimabobs</h1>
    <p class="text-center">From a small, local company 👍</p>
    <ul class="gad__grid">
        <li class="gad__item">
            <a href="#">
                <img class="gad__img" src="./data/bread.jpg" alt="" />
                <div class="gad__content">
                    <h3 class="gad__title">Bread Basket</h3>
                    <div class="gad__flex">
                        <p class="gad__desc">This bread was baked 9 years ago, but it has enough preservatives to keep it alive for another 9 more!</p>
                        <p class="gad__price"><span>$</span>8.00</p>
                    </div>
                </div>
            </a>
        </li>

        <li class="gad__item">
            <a href="#">
                <img class="gad__img" src="./data/funny-dog.jpg" alt="" />
                <div class="gad__content">
                    <h3 class="gad__title">Funny Glasses</h3>
                    <div class="gad__flex">
                        <p class="gad__desc">These glasses make you cool outside, but most definitely not inside.</p>
                        <p class="gad__price"><span>$</span>12.00</p>
                    </div>
                </div>
            </a>
        </li>

        <li class="gad__item">
            <a href="#">
                <img class="gad__img" src="./data/storm-trooper.jpg" alt="" />
                <div class="gad__content">
                    <h3 class="gad__title">Lego Storm Trooper</h3>
                    <div class="gad__flex">
                        <p class="gad__desc">This lego figurine will defend you till the end of time, or until it melts in your car on a sunny day.</p>
                        <p class="gad__price"><span>$</span>2.00</p>
                    </div>
                </div>
            </a>
        </li>
        
    </ul>
</body>

</html>