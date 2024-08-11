<?php
require_once './models/product.php';
session_start();

if (!$_SESSION['loggedIn']) {
    header('Location: ./products.php');
    exit();
}

include './conn/openDb.php';
include './logic/EchoImage.php';

$sql = 'SELECT Products.Name,Products.ProductId,Products.ImageId,Products.Description,Products.Price,Images.Path FROM Products LEFT JOIN Images ON Products.ImageId = Images.ImageId WHERE Products.ProductId = ?';

$prodIds = [(int)$_SESSION['products'][0]->productId];

for ($i=0; $i < sizeof($_SESSION['products']); $i++) {
    $sql .= ' OR Products.ProductId = ?';
    array_push($prodIds, (int)$_SESSION['products'][$i]->productId);
}

$prodQuery = $db->prepare($sql);
$prodQuery->execute($prodIds);
$prodRes = $prodQuery->fetchAll(PDO::FETCH_ASSOC);
$cartEmpty = sizeof($_SESSION['products']) == 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Cart - <?= $_SESSION['username'] ?></title>
</head>
<body>
<?php include "./components/header.php" ?>
    <h1 class="main-header"><?= $_SESSION['username'] ?>'s Cart</h1>

    <?php if (!$cartEmpty): ?>
        <div class="cart-grid">
            <?php 
                foreach($prodRes as $prod):
                    $prodAmount = null;

                    foreach($_SESSION['products'] as $cartProduct) {

                        if ($cartProduct->productId == $prod['ProductId']) {
                            $prodAmount = $cartProduct->amount;
                            break;
                        }
                    }
                    
                    $imageExists = false;
                    if (!empty($prod['Path'])) {
                        $imageExists = true;
                    }
            ?>
                <div class="cart-grid__item">
                    <img src="<?= $imageExists ? EchoImage($prod['Path']) : './images/default-image.jpg' ?>" alt="<?= $prod['Name'] ?>" />

                    <div class="cart-grid__item-content">
                        <h2><?= $prod['Name'] ?></h2>
                        <p><?= $prod['Description'] ?></p>
                        <div class="cart-grid__item-flex">
                            <div>
                                <p>Amount:</p>
                                <select data-iniAmount="<?= $prodAmount ?>" data-prodId="<?= $prod['ProductId'] ?>" class="cart-select">
                                    <?php 
                                for ($i=1; $i<6; $i++): 
                                    $isSelected = $prodAmount == $i
                                    ?>
                                    <option <?= $isSelected ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                                
                            <p class="cart-grid__item-price">$<?= $prod['Price'] ?></p>
                        </div>
                        <button class="cart-grid__item-deletebtn" data-prodId="<?= $prod['ProductId'] ?>" title="remove item from cart">
                        <svg id="Icons" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm4.707,14.293a1,1,0,1,1-1.414,1.414L12,13.414,8.707,16.707a1,1,0,1,1-1.414-1.414L10.586,12,7.293,8.707A1,1,0,1,1,8.707,7.293L12,10.586l3.293-3.293a1,1,0,1,1,1.414,1.414L13.414,12Z"/></svg>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="checkout-wrapper">
            <h3 class="text-center">Ready to checkout?</h3>
            <a href="./logic/clearCart.php" class="checkout__btn text-center">
                <p>Checkout</p>
                <svg viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg"><rect fill="none" height="256" width="256"/><path d="M223.9,65.4l-12.2,66.9A24,24,0,0,1,188.1,152H72.1l4.4,24H184a24,24,0,1,1-24,24,23.6,23.6,0,0,1,1.4-8H102.6a23.6,23.6,0,0,1,1.4,8,24,24,0,1,1-42.2-15.6L34.1,32H16a8,8,0,0,1,0-16H34.1A16,16,0,0,1,49.8,29.1L54.7,56H216a7.9,7.9,0,0,1,6.1,2.9A7.7,7.7,0,0,1,223.9,65.4Z"/></svg>
            </a>
        </div>
    <?php else: ?>
        <div class="cart-empty text-center">
            <h3>Your cart is empty!</h3>
            <p>Go to the <a href="products.php">products</a> page to fill your cart.</p>
        </div>
    <?php endif; ?>

    

    <script type="text/javascript">
        const amountSelector = document.querySelectorAll('.cart-select');
        const deleteBtn = document.querySelectorAll('.cart-grid__item-deletebtn');

        amountSelector.forEach(el => {
            el.addEventListener('change', (e) => {
                const targetAmount = parseInt(e.currentTarget.value);
                const productId = parseInt(e.currentTarget.dataset['prodid']);
                // const startAmount = e.target.dataset['iniamount'];
                if (isNaN(targetAmount) || isNaN(productId)) return;

                fetch('/logic/updateCart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        targetAmount,
                        productId
                    })
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('could not update cart');
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })

            });
        });

        deleteBtn.forEach(el => {
            el.addEventListener('click', (e) => {
                const productId = parseInt(e.currentTarget.dataset['prodid']);
                if (isNaN(productId)) return;

                fetch('/logic/removeItem.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        productId
                    })
                })
                .then(res => {
                    if (!res.ok) return;
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })

            })
        })

    </script>
</body>
</html>