<?php
require_once './models/product.php';
session_start();

if (!$_SESSION['loggedIn']) {
    header("Location: index.php");
    exit();
}

    include "./conn/openDb.php";
    include "./logic/EchoImage.php";
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
        $products = $db->query("SELECT Products.ProductId,Products.Name,Products.ImageId,Products.Description,Products.Price,Images.Path FROM Products LEFT JOIN Images ON Products.ImageId = Images.ImageId")->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as $prod):
            $imageExists = false;

            if ($prod['Path'] && file_exists($prod['Path']) && is_readable($prod['Path']) && exif_imagetype($prod['Path'])) {
                $imageExists = true;
            }
        ?>
            <li class="gad__item">
                <a href="description.php?prodId=<?= $prod['ProductId'] ?>">
                    <img class="gad__img" src="<?= $imageExists ? EchoImage(htmlspecialchars($prod['Path'])) : './images/default-image.jpg' ?>" alt="<?= htmlspecialchars($prod['Name']) ?>" />
                    <div class="gad__content">
                        <h3 class="gad__title"><?= htmlspecialchars($prod['Name']) ?></h3>
                        <div class="gad__flex">
                            <p class="gad__desc"><?= htmlspecialchars($prod['Description']) ?>
                        </p>
                            <p class="gad__price"><span>$</span><?= htmlspecialchars($prod['Price']) ?></p>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>

    </ul>
</body>

</html>