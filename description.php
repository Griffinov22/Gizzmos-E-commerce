<?php
require_once './models/product.php';
session_start();
include './conn/openDb.php';
include './logic/EchoImage.php';

if (empty($_GET) ||
 !isset($_GET['prodId']) ||
  !$_SESSION['loggedIn'] || !is_numeric($_GET['prodId'])) {
    header('Location: ./products.php');
    exit();
}

$prodId = $_GET['prodId'];
$prodQuery = $db->prepare("SELECT Products.Name,Products.ImageId,Products.Description,Products.Price,Images.Path FROM Products LEFT JOIN Images ON Products.ImageId = Images.ImageId WHERE Products.ProductId = ?");

$prodQuery->execute([$prodId]);
$prod = $prodQuery->fetch(PDO::FETCH_ASSOC);

$imageExists = false;
if (!empty($prod['Path'])) {
    $imageExists = true;
}

$inCart = false;
foreach ($_SESSION['products'] as $products) {
    if ($products->productId == $prodId)
        $inCart = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Description - <?= htmlentities($prod['Name']) ?></title>
</head>
<body>
<?php include "./components/header.php" ?>
    <div class="prodcard">
        <div class="prodcard__content">
            <h1 class="prodcard__title"><?= htmlentities($prod['Name']) ?></h1>
                <?php if ($inCart): ?>
                    <div>
                        <p class="incart-txt">This item is already in your cart
                            <svg viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg"><path d="M504.717 320H211.572l6.545 32h268.418c15.401 0 26.816 14.301 23.403 29.319l-5.517 24.276C523.112 414.668 536 433.828 536 456c0 31.202-25.519 56.444-56.824 55.994-29.823-.429-54.35-24.631-55.155-54.447-.44-16.287 6.085-31.049 16.803-41.548H231.176C241.553 426.165 248 440.326 248 456c0 31.813-26.528 57.431-58.67 55.938-28.54-1.325-51.751-24.385-53.251-52.917-1.158-22.034 10.436-41.455 28.051-51.586L93.883 64H24C10.745 64 0 53.255 0 40V24C0 10.745 10.745 0 24 0h102.529c11.401 0 21.228 8.021 23.513 19.19L159.208 64H551.99c15.401 0 26.816 14.301 23.403 29.319l-47.273 208C525.637 312.246 515.923 320 504.717 320zM408 168h-48v-40c0-8.837-7.163-16-16-16h-16c-8.837 0-16 7.163-16 16v40h-48c-8.837 0-16 7.163-16 16v16c0 8.837 7.163 16 16 16h48v40c0 8.837 7.163 16 16 16h16c8.837 0 16-7.163 16-16v-40h48c8.837 0 16-7.163 16-16v-16c0-8.837-7.163-16-16-16z" fill="#006303" class="fill-000000"></path></svg>
                        </p>
                    </div>
                        <?php endif; ?>
                        <p class="prodcard__desc"><?= htmlentities($prod['Description']) ?? '<i>no description was added</i>' ?></p>
                        <p class="prodcard__price">$<?= htmlentities($prod['Price']) ?></p>
                        <div class="prodcard__amount-wrapper">
                            <p>Amount:</p>
                            <select id="amount" <?= $inCart ? 'disabled title="go to your cart to edit"' : '' ?>>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
                <a href="./logic/addToCart.php?productId=<?= htmlentities($_GET['prodId']) ?>&amount=1" class="prodcard__cartbtn">Add To Cart</a>    
        </div>

            console.log(url);
            <img class="prodcard__img" src="<?= $imageExists ? EchoImage(htmlentities($prod['Path'])) : './images/default-image.jpg' ?>" alt="<?= htmlentities($prod['Name']) ?>" />
    </div>

    <script type="text/javascript">
        cartBtn = document.querySelector('.prodcard__cartbtn');
        amtSelect = document.querySelector('#amount');

        const urlBase = new URL(cartBtn.href);
        const params = new URLSearchParams(urlBase.search);

        const prodId = <?= htmlentities($_GET['prodId']) ?>;

        amtSelect.addEventListener('change', (e) => {
            params.set('productId', prodId);
            params.set('amount', e.target.value);

            const url = urlBase.origin + urlBase.pathname + "?" + params;
            cartBtn.href = url;
        })

    </script>
</body>
</html>