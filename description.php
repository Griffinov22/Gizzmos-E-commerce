<?php
session_start();

if (empty($_GET) || !isset($_GET['prodId'])) {
    header('Location: ./products.php');
    exit();
}

include './conn/openDb.php';
include './logic/EchoImage.php';

$prodId = $_GET['prodId'];

$prodQuery = $db->prepare("SELECT Products.Name,Products.ImageId,Products.Description,Products.Price,Images.Path FROM Products LEFT JOIN Images ON Products.ImageId = Images.ImageId WHERE Products.ProductId = ?");

$prodQuery->execute([$prodId]);
$prod = $prodQuery->fetch(PDO::FETCH_ASSOC);

$imageExists = false;
if (!empty($prod['Path'])) {
    $imageExists = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Description - <?= $prod['Name'] ?></title>
</head>
<body>
<?php include "./components/header.php" ?>
    <div class="prodcard">
        <div class="prodcard__content">
            <h1 class="prodcard__title"><?= $prod['Name'] ?></h1>
                <p class="prodcard__desc"><?= $prod['Description'] ?? '<i>no description was added</i>' ?></p>
                <p class="prodcard__price">$<?= $prod['Price'] ?></p>
            <button class="prodcard__cartbtn">Add To Cart</button>    
        </div>

        <img class="prodcard__img" src="<?= $imageExists ? EchoImage($prod['Path']) : './images/default-image.jpg' ?>" alt="<?= $prod['Name'] ?>" />
    </div>
</body>
</html>